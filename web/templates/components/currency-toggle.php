<?php
// Enhanced currency toggle with persistence
$current_currency = $_SESSION['display_currency'] ?? $_COOKIE['preferred_currency'] ?? 'EUR';
$current_url = $_SERVER['REQUEST_URI'];
$base_url = strtok($current_url, '?'); // Remove existing query parameters

// Preserve all existing query parameters except currency
$query_params = [];
parse_str(parse_url($current_url, PHP_URL_QUERY), $query_params);
unset($query_params['currency']); // Remove currency to avoid duplication

// Build new query strings
$eur_query = http_build_query(array_merge($query_params, ['currency' => 'EUR']));
$usd_query = http_build_query(array_merge($query_params, ['currency' => 'USD']));

$eur_url = $base_url . ($eur_query ? '?' . $eur_query : '');
$usd_url = $base_url . ($usd_query ? '?' . $usd_query : '');
?>

<div class="currency-toggle" style="display: flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 4px; border-radius: 8px;">
  <a href="<?= htmlspecialchars($eur_url) ?>" 
     class="currency-btn <?= $current_currency === 'EUR' ? 'active' : '' ?>" 
     style="padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; <?= $current_currency === 'EUR' ? 'background: white; color: #0066cc; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #64748b;'; ?>">
    € EUR
  </a>
  <a href="<?= htmlspecialchars($usd_url) ?>" 
     class="currency-btn <?= $current_currency === 'USD' ? 'active' : '' ?>" 
     style="padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; <?= $current_currency === 'USD' ? 'background: white; color: #0066cc; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #64748b;'; ?>">
    $ USD
  </a>
</div>

<script>
// Enhanced currency persistence with localStorage fallback
(function() {
  // Set cookie for currency preference (30 days)
  function setCurrencyCookie(currency) {
    const date = new Date();
    date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = `preferred_currency=${currency}; expires=${date.toUTCString()}; path=/; SameSite=Lax`;
  }
  
  // Get currency from localStorage or cookie
  function getStoredCurrency() {
    // Try localStorage first
    if (localStorage.getItem('preferred_currency')) {
      return localStorage.getItem('preferred_currency');
    }
    
    // Fallback to cookie
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
      const [name, value] = cookie.trim().split('=');
      if (name === 'preferred_currency') {
        return value;
      }
    }
    
    return null;
  }
  
  // Store currency preference
  function storeCurrencyPreference(currency) {
    localStorage.setItem('preferred_currency', currency);
    setCurrencyCookie(currency);
  }
  
  // Auto-detect and apply stored currency on page load
  const storedCurrency = getStoredCurrency();
  if (storedCurrency && !document.querySelector('.currency-btn.active')) {
    // If no active currency button but we have stored preference
    const targetBtn = document.querySelector(`.currency-btn[href*="currency=${storedCurrency}"]`);
    if (targetBtn) {
      // Redirect to apply stored currency
      const currentUrl = new URL(window.location);
      currentUrl.searchParams.set('currency', storedCurrency);
      window.location.href = currentUrl.toString();
    }
  }
  
  // Add click handlers to currency buttons
  document.querySelectorAll('.currency-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      const currencyMatch = href.match(/[?&]currency=([A-Z]{3})/);
      if (currencyMatch) {
        storeCurrencyPreference(currencyMatch[1]);
      }
    });
  });
})();
</script>
