<?php

declare(strict_types=1);

namespace Streicher\App\Services;

/**
 * GitHubSyncService - Syncs CSV database changes to GitHub
 * Uses GITHUB_PAT for authentication
 */
class GitHubSyncService
{
    private string $repoPath;
    private string $githubPat;
    private string $githubRepo;
    private bool $enabled;

    public function __construct()
    {
        $this->repoPath = dirname(__DIR__, 2); // Project root
        $this->githubPat = $_ENV['GITHUB_PAT'] ?? '';
        $this->githubRepo = $this->getRepoFromGitConfig();
        $this->enabled = !empty($this->githubPat) && !empty($this->githubRepo);
    }

    /**
     * Sync CSV database files to GitHub
     * Called after write operations
     */
    public function sync(array $tables = []): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'error' => 'GitHub sync not configured'];
        }

        $results = [];
        
        // Configure git credentials
        $this->exec("git config user.email \"sync@streichergmbh.com\"");
        $this->exec("git config user.name \"Streicher Sync Bot\"");
        
        // Stage specified tables or all CSV files
        if (empty($tables)) {
            $this->exec("git add data/db/*.csv");
        } else {
            foreach ($tables as $table) {
                $this->exec("git add data/db/{$table}.csv");
            }
        }
        
        // Check if there are changes to commit
        $status = $this->exec("git status --porcelain data/db/");
        if (empty($status)) {
            return ['success' => true, 'message' => 'No changes to sync'];
        }
        
        // Commit with timestamp
        $timestamp = date('Y-m-d H:i:s');
        $commitMsg = "Auto-sync: DB update at {$timestamp}";
        $commitResult = $this->exec("git commit -m \"{$commitMsg}\"");
        
        if (empty($commitResult) && $this->getExitCode() !== 0) {
            return ['success' => false, 'error' => 'Failed to commit changes'];
        }
        
        // Push using PAT for authentication
        $remoteUrl = $this->getAuthenticatedRemoteUrl();
        $pushResult = $this->exec("git push {$remoteUrl} main 2>&1");
        
        // Reset remote to original to not expose PAT in config
        $this->exec("git remote set-url origin https://github.com/{$this->githubRepo}.git");
        
        return [
            'success' => strpos($pushResult, 'error') === false && strpos($pushResult, 'fatal') === false,
            'message' => 'Sync completed',
            'details' => $pushResult
        ];
    }

    /**
     * Manual sync with custom message (for admin dashboard)
     */
    public function manualSync(string $message = 'Manual refresh'): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'error' => 'GitHub sync not configured - check GITHUB_PAT'];
        }

        // First, pull any remote changes to avoid conflicts
        $pullResult = $this->pullLatest();
        if (!$pullResult['success']) {
            return $pullResult;
        }

        // Then push local changes
        return $this->sync();
    }

    /**
     * Pull latest changes from GitHub (for admin refresh)
     */
    public function pullLatest(): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'error' => 'GitHub sync not configured'];
        }

        // Stash any local changes first
        $this->exec("git stash");
        
        // Fetch and pull
        $remoteUrl = $this->getAuthenticatedRemoteUrl();
        $fetchResult = $this->exec("git fetch {$remoteUrl} main 2>&1");
        $pullResult = $this->exec("git pull {$remoteUrl} main --rebase 2>&1");
        
        // Restore stashed changes if any
        $this->exec("git stash pop 2>/dev/null || true");
        
        // Reset remote URL
        $this->exec("git remote set-url origin https://github.com/{$this->githubRepo}.git");
        
        $success = strpos($pullResult, 'error') === false && strpos($pullResult, 'fatal') === false;
        
        return [
            'success' => $success,
            'message' => $success ? 'Latest data pulled from GitHub' : 'Pull failed - may have conflicts',
            'details' => $pullResult
        ];
    }

    /**
     * Get sync status for admin dashboard
     */
    public function getStatus(): array
    {
        if (!$this->enabled) {
            return [
                'enabled' => false,
                'error' => 'GITHUB_PAT not configured'
            ];
        }

        $lastCommit = $this->exec("git log -1 --format='%h %s %ci' -- data/db/");
        $pendingChanges = $this->exec("git status --porcelain data/db/");
        $remoteUrl = $this->exec("git remote get-url origin");

        return [
            'enabled' => true,
            'last_sync' => $lastCommit,
            'pending_changes' => !empty($pendingChanges),
            'repo' => $this->githubRepo,
            'tables' => $this->getCsvTables()
        ];
    }

    /**
     * Get list of CSV tables in data/db
     */
    private function getCsvTables(): array
    {
        $tables = [];
        $dbPath = $this->repoPath . '/data/db';
        if (is_dir($dbPath)) {
            foreach (glob($dbPath . '/*.csv') as $file) {
                $tables[] = basename($file, '.csv');
            }
        }
        return $tables;
    }

    /**
     * Execute shell command and return output
     */
    private function exec(string $command): string
    {
        $cwd = getcwd();
        chdir($this->repoPath);
        
        $output = shell_exec($command . ' 2>&1');
        
        chdir($cwd);
        return $output ?? '';
    }

    /**
     * Get last exit code
     */
    private function getExitCode(): int
    {
        return (int)shell_exec('echo $?');
    }

    /**
     * Extract repo name from git config
     */
    private function getRepoFromGitConfig(): string
    {
        $remoteUrl = $this->exec("git remote get-url origin 2>/dev/null");
        if (preg_match('/github\.com[:\/]([^\/]+)\/([^\/\.]+)/', $remoteUrl, $matches)) {
            return $matches[1] . '/' . $matches[2];
        }
        return '';
    }

    /**
     * Get remote URL with PAT embedded for auth
     */
    private function getAuthenticatedRemoteUrl(): string
    {
        return "https://" . $this->githubPat . "@github.com/" . $this->githubRepo . ".git";
    }
}
