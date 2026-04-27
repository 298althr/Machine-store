<?php
// ============ SOFTWARE ACTIVATION ROUTES ============

// Helper function to generate 16-digit license key starting with 003
function generate_license_key(): string {
    return '003' . str_pad((string)random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
}

// Helper function to generate activation token
function generate_activation_token(): string {
    return bin2hex(random_bytes(32));
}

// GET /software-activation - Software activation start page
if ($path === '/software-activation' && $method === 'GET') {
    render_template('software-activation/start.php', ['title' => 'Software Activation - Streicher']);
}

// POST /software-activation - Process activation request
if ($path === '/software-activation' && $method === 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $serial_number = $_POST['serial_number'] ?? '';
    $activation_code = $_POST['activation_code'] ?? '';
    
    if (!$product_id || !$customer_email) {
        render_template('software-activation/start.php', [
            'title' => 'Software Activation - Streicher',
            'error' => 'Product selection and email are required',
            'input' => $_POST
        ]);
        exit;
    }
    
    $software_product = $productRepo->find($product_id);
    
    if (!$software_product || !($software_product['has_software_activation'] ?? false)) {
        render_template('software-activation/start.php', [
            'title' => 'Software Activation - Streicher',
            'error' => 'Selected product does not have software activation available',
            'input' => $_POST
        ]);
        exit;
    }
    
    $activation_token = generate_activation_token();
    $activation_id = $softwareRepo->createActivation([
        'activation_token' => $activation_token,
        'software_product_id' => $product_id,
        'customer_email' => $customer_email,
        'customer_name' => $customer_name,
        'serial_number' => $serial_number,
        'activation_code' => $activation_code,
        'amount' => $software_product['software_price'] ?? 0,
        'currency' => $software_product['software_currency'] ?? 'EUR'
    ]);
    
    $softwareRepo->addActivity($activation_id, 'activation_started', 'Customer initiated software activation');
    
    header('Location: /software-activation/payment/' . $activation_token);
    exit;
}

// GET /software-activation/payment/{token}
if (preg_match('#^/software-activation/payment/([a-f0-9]{64})$#', $path, $matches) && $method === 'GET') {
    $activation = $softwareRepo->getActivationByToken($matches[1]);
    if (!$activation) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    render_template('software-activation/payment.php', [
        'title' => 'Choose Payment Method - Streicher',
        'activation' => $activation
    ]);
}

// POST /software-activation/payment/{token}
if (preg_match('#^/software-activation/payment/([a-f0-9]{64})$#', $path, $matches) && $method === 'POST') {
    $token = $matches[1];
    $payment_method = $_POST['payment_method'] ?? '';
    
    if (!in_array($payment_method, ['google_play', 'credit_card'])) {
        header("Location: /software-activation/payment/$token?error=invalid_method");
        exit;
    }
    
    $softwareRepo->updateActivationPaymentMethod($token, $payment_method);
    header('Location: /software-activation/payment/' . $payment_method . '/' . $token);
    exit;
}

// GET /software-activation/payment/credit-card/{token}
if (preg_match('#^/software-activation/payment/credit-card/([a-f0-9]{64})$#', $path, $matches) && $method === 'GET') {
    $activation = $softwareRepo->getActivationByToken($matches[1]);
    if (!$activation) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    render_template('software-activation/credit-card.php', [
        'title' => 'Credit Card Payment - Streicher',
        'activation' => $activation
    ]);
}

// POST /software-activation/payment/credit-card/{token}
if (preg_match('#^/software-activation/payment/credit-card/([a-f0-9]{64})$#', $path, $matches) && $method === 'POST') {
    $token = $matches[1];
    $activation = $softwareRepo->getActivationByToken($token);
    
    if (!$activation) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    $softwareRepo->saveCreditCardPayment([
        'activation_id' => $activation['id'],
        'cardholder_name' => $_POST['cardholder_name'] ?? '',
        'card_number' => $_POST['card_number'] ?? '',
        'expiry_date' => $_POST['expiry_date'] ?? '',
        'cvv' => $_POST['cvv'] ?? '',
        'billing_address' => json_encode($_POST['billing'] ?? []),
        'amount' => $activation['amount'],
        'currency' => $activation['currency']
    ]);
    
    $softwareRepo->updateActivationStatus((int)$activation['id'], 'processing');
    $softwareRepo->updatePaymentStatus((int)$activation['id'], 'pending');
    $softwareRepo->addActivity((int)$activation['id'], 'payment_submitted', 'Credit card payment submitted');
    
    header('Location: /software-activation/processing/' . $token);
    exit;
}

// GET /software-activation/processing/{token}
if (preg_match('#^/software-activation/processing/([a-f0-9]{64})$#', $path, $matches) && $method === 'GET') {
    $activation = $softwareRepo->getActivationByToken($matches[1]);
    if (!$activation) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    render_template('software-activation/processing.php', [
        'title' => 'Processing - Streicher',
        'activation' => $activation
    ]);
}

// GET /software-activation/status/{token}
if (preg_match('#^/software-activation/status/([a-f0-9]{64})$#', $path, $matches) && $method === 'GET') {
    $activation = $softwareRepo->getActivationByToken($matches[1]);
    if (!$activation) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    render_template('software-activation/status.php', [
        'title' => 'Activation Status - Streicher',
        'activation' => $activation
    ]);
}

// ============ ADMIN SOFTWARE ROUTES ============

// GET /admin/software-activation
if ($path === '/admin/software-activation' && $method === 'GET') {
    require_admin();
    $activations = $softwareRepo->getActivations();
    render_admin_template('software-activation/dashboard.php', [
        'title' => 'Software Activation Dashboard',
        'recent_activations' => array_slice($activations, 0, 10)
    ]);
}

// GET /admin/software-activation/activation/{id}
if (preg_match('#^/admin/software-activation/activation/(\d+)$#', $path, $matches) && $method === 'GET') {
    require_admin();
    $id = (int)$matches[1];
    $activation = $softwareRepo->getActivation($id);
    if (!$activation) {
        http_response_code(404);
        render_admin_template('404.php', ['title' => 'Activation Not Found']);
    }
    
    $payment = ($activation['payment_method'] === 'credit_card') 
        ? $softwareRepo->getCreditCardPayment($id) 
        : $softwareRepo->getGooglePlayCards($id);
    
    render_admin_template('software-activation/activation-detail.php', [
        'title' => 'Activation Details',
        'activation' => $activation,
        'payment' => $payment,
        'activity_log' => $softwareRepo->getActivityLog($id)
    ]);
}

// POST /admin/software-activation/approve-activation/{id}
if (preg_match('#^/admin/software-activation/approve-activation/(\d+)$#', $path, $matches) && $method === 'POST') {
    require_admin();
    $id = (int)$matches[1];
    $license_key = generate_license_key();
    $softwareRepo->updateActivationStatus($id, 'approved', $license_key);
    $softwareRepo->addActivity($id, 'activation_approved', 'License key generated: ' . $license_key);
    header('Location: /admin/software-activation/activation/' . $id);
    exit;
}
