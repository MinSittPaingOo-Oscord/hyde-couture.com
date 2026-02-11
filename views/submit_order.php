<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order and Payment Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

.checkout-page {
    --color-primary-dark: #0f4f1d;
    --color-accent-green: #e5f6e5;
    --color-border-green: #38a848;
    --color-text-dark: #333;
    --color-text-light: #666;
    --color-white: #ffffff;

    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    padding: 20px;
    display: flex;
    justify-content: center;
}

/* Replace body styles */
.checkout-page .main-container {
    background-color: var(--color-white);
    margin: 0 auto;
}

/* Scope global elements */
.checkout-page hr {
    border: 0;
    border-top: 1px solid #eee;
    margin: 30px 0;
}

/* Prevent modal affecting whole site */
.checkout-page .modal,
.checkout-page .modal-full-view {
    position: fixed;
}

/* Safety: prevent layout leaks */
.checkout-page * {
    box-sizing: border-box;
}

        .main-container {
            background-color: var(--color-white);
            padding: 30px;
            max-width: 800px; /* Max-width from test2.html */
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* --- Global / test.html styles --- */
        .order-submit-section {
            padding-bottom: 30px;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .order-submit-section h1 {
            color: var(--color-primary-dark);
            font-size: 28px;
            font-weight: bold;
            text-align: left;
            margin-bottom: 30px;
        }

        .section-header {
            font-size: 14px;
            color: var(--color-text-light);
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* 1. High-Level Payment */
        .payment-options-list {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .payment-option-label {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #ccc;
            background-color: white;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        .payment-option-label.selected {
            border-color: var(--color-border-green);
            color: var(--color-primary-dark);
            background-color: var(--color-accent-green);
            font-weight: bold;
        }

        /* Hidden radio button */
        .payment-options-list input[type="radio"] {
            display: none;
        }
        /* End High-Level Payment MODIFICATION */

        /* 2. Delivery Address */
        .address-box {
            padding: 15px;
            border: 1px solid var(--color-border-green);
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .address-box p {
            margin: 0;
            color: var(--color-text-dark);
            line-height: 1.4;
        }

        .add-new-address-btn {
            width: 100%;
            padding: 12px;
            background-color: white;
            border: 1px solid #ccc;
            color: var(--color-text-light);
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
        }

        .add-new-address-btn i {
            margin-right: 5px;
        }

        

        /* 3. Detailed Payment Selection */
        .detailed-payment-selection {
            /* Grouping div for conditional display */
            transition: all 0.3s ease-in-out;
        }
        
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
            max-width: 500px; /* Constraint from test.html container */
            width: 95%;
        }
        .payment-card.selected-card {
    border-color: var(--color-border-green);
    background-color: var(--color-accent-green);
}

        .payment-card:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        

        .card-details {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 15px;
        }

        .text-info {
            display: flex;
            flex-direction: column;
        }

        .type {
            font-size: 12px;
            color: var(--color-text-light);
        }

        .provider {
            font-size: 16px;
            font-weight: bold;
            color: var(--color-text-dark);
        }

        /* Radio Button Custom Styling */
        .payment-card input[type="radio"] {
            display: none;
        }

        .checkmark {
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 50%;
            border: 1px solid #ccc;
            display: block;
            position: relative;
        }

        .payment-card input[type="radio"]:checked + .checkmark {
            background-color: white;
            border-color: var(--color-border-green);
        }

        .payment-card input[type="radio"]:checked + .checkmark:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--color-border-green);
            transform: translate(-50%, -50%);
        }
        /* Highlight the selected address card */
.selected-address {
    border: 2px solid var(--color-border-green) !important;
    background-color: var(--color-accent-green) !important;
}

.selected-address .address-accordion-title {
    background-color: var(--color-accent-green) !important;
}
        /* 4. Security Disclaimer */
        .security-disclaimer {
            text-align: center;
            font-size: 12px;
            color: var(--color-text-light);
            margin-top: 30px;
            max-width: 500px; /* Constraint from test.html container */
        }


        /* --- test2.html styles (Confirmation/Summary) --- */
        .payment-confirmation-section {
            /* Grouping div for conditional display */
            transition: all 0.3s ease-in-out;
        }

        .section-title {
            color: var(--color-primary-dark);
            font-size: 16px;
            font-weight: bold;
            text-align: left;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            display: inline-block;
            
        }

        /* Top Payment Section (KBZ Pay + QR) */
        .top-payment-qr {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            padding-bottom: 20px;
            margin-right: 10px;

            border-bottom: 1px solid #ddd;
        }

        /* Change this in your <style> section */
.kbz-pay-info {
    border: 1px solid #eee;
    padding: 20px;
    border-radius: 4px;
    /* width: 600px;  <-- REMOVE THIS */
    flex: 1;       /* <-- ADD THIS: It will take up the remaining space */
    margin-right: 20px; /* Add some breathing room before the QR code */
}

        .logo-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .kbz-logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo-text-info {
            display: flex;
            flex-direction: column;
        }

        .mobile-wallet-text {
            font-size: 12px;
            color: var(--color-text-light);
        }

        .kbz-pay-provider {
            font-size: 16px;
            font-weight: bold;
            color: var(--color-primary-dark);
        }

        .detail-row {
            display: grid;
            grid-template-columns: 80px 1fr;
            font-size: 14px;
            color: var(--color-text-dark);
            line-height: 1.8;
        }

        .detail-label {
            font-weight: normal;
            color: var(--color-primary-dark);
        }

        .detail-value {
            font-weight: bold;
        }

        .qr-code-section {
    text-align: center;
    flex-shrink: 0; /* Prevents the QR section from getting squashed */
}

.qr-code-img {
    width: 150px; /* Reduced from 220px to fit better in the 800px container */
    height: 150px;
    margin-bottom: 5px;
}
        .scan-here-text {
            font-size: 12px;
            color: var(--color-primary-dark);
            font-weight: bold;
        }

        /* Payment Slip & Policy Section */
        .slip-policy-container { /* New container for the elements that were previously flexed */
        margin-bottom: 30px;
        }

        .payment-slip-upload {
            /* flex: 1; -> Removed */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 20px; /* Increased top margin for separation from QR section */
            margin-bottom: 20px; /* Added bottom margin for separation from policy */
            background-color: var(--color-white);
            width: 100%; /* Make it full width */
            box-sizing: border-box;
        }

        .slip-header {
            font-size: 14px;
            color: var(--color-primary-dark);
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px 0;
            border-bottom: 2px solid var(--color-border-green);
        }
        
        /* New Styles for File Upload Buttons and Status */
        .upload-section {
            margin-top: 20px;
            padding: 15px;
            border-top: 1px solid #ddd;
            display: flex; /* Arrange buttons side-by-side */
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .action-btn {
            background-color: var(--color-border-green); /* Green button */
            color: var(--color-white);
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-grow: 1; /* Allow buttons to grow */
            justify-content: center;
        }

        .action-btn:hover {
            background-color: var(--color-primary-dark);
        }

        .file-status {
            width: 100%;
            margin-top: 10px;
            font-style: italic;
            color: #666;
            text-align: center;
        }


        .policy-info {
            /* flex: 1; -> Removed */
            padding: 20px;
            background-color: var(--color-accent-green);
            border-radius: 4px;
            color: var(--color-text-dark);
            line-height: 1.5;
            position: relative;
        }

        .policy-info > div {
            padding-left: 15px;
        }

        .policy-header {
            color: var(--color-primary-dark);
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 0;
        }

        .policy-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .policy-list li {
            margin-bottom: 8px;
        }

        .policy-list li::before {
            content: "•";
            color: var(--color-primary-dark);
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .return-policy-header {
            color: var(--color-primary-dark);
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        .return-policy-text {
            font-size: 14px;
        }


        /* Order Summary */
        .order-summary {
            border: 2px solid var(--color-primary-dark);
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .summary-header {
            font-size: 14px;
            color: var(--color-primary-dark);
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--color-border-green);
            display: inline-block;
            padding-bottom: 5px;
        }

        .summary-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px 20px; /* Increased gap for better readability */
    font-size: 14px;
    color: var(--color-text-dark);
    align-items: center; /* Changed from baseline to center */
}

        .summary-item {
            display: contents;
        }

        .summary-label {
            grid-column: 1 / 2;
            text-align: left;
            color: var(--color-text-light);
        }

        .summary-value {
            grid-column: 2 / 3;
            text-align: right;
            font-weight: bold;
        }

        .subtotal-row, .delivery-row {
            border-bottom: 1px dashed #ddd;
            padding-bottom: 5px;
        }

        .total-amount-row {
            margin-top: 15px;
            border-top: 2px solid var(--color-primary-dark);
            padding-top: 10px;
        }

        .total-amount-label {
            font-size: 16px;
            font-weight: bold;
            color: var(--color-primary-dark);
        }

        .total-amount-value {
            font-size: 18px;
            font-weight: bolder;
            color: var(--color-primary-dark);
        }

        .payment-method-row {
            grid-column: 1 / 3;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }

        .payment-method-details {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            color: var(--color-primary-dark);
        }

        .payment-method-logo {
            width: 40px;
            height: 40px;
        }

        .address-value {
    text-align: right;
    font-weight: normal;
    line-height: 1.4;
    max-width: 300px; /* Increased slightly */
    margin-left: auto;
}

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--color-primary-dark);
            color: var(--color-white);
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 30px;
            border-radius: 4px;
            letter-spacing: 1px;
        }
        /* --- Modal Styles (For the New Address Pop-up) --- */

/* 1. The Modal Background */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    backdrop-filter: blur(2px); /* Optional: Adds a nice blur effect */
}


/* 2. The Modal Content Box (MODIFIED) */
.modal-content {
    background-color: var(--color-white);
    /* Change 1: Position closer to the top and center */
    margin: 5% auto; 
    padding: 30px; /* Increased padding */
    border: 1px solid #888;
    /* Change 2: Significantly larger width */
    width: 50%; 
    /* Change 3: Max height to keep content contained and allow scrolling if needed */
    max-height: 110vh; /* 90% of the viewport height */
    overflow-y: auto; /* Enable vertical scroll for content overflow */
    box-shadow: 0 8px 16px rgba(0,0,0,0.3); /* Stronger shadow */
    border-radius: 8px;
    position: relative;
}

/* 3. Modal Header */
.modal-header {
    color: var(--color-primary-dark);
    font-size: 24px; /* Larger font size */
    font-weight: bold;
    text-align: left;
    margin-bottom: 30px; /* More spacing */
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

/* 4. Close Button */
.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 15px;
}

.close-btn:hover,
.close-btn:focus {
    color: var(--color-primary-dark);
    text-decoration: none;
    cursor: pointer;
}

/* 5. Input Styling (Dropdowns) */
.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: none; /* Hide standard label if you want the placeholder style */
}

.dropdown-input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--color-border-green); /* Green border */
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    -webkit-appearance: none; /* Remove default browser styling */
    -moz-appearance: none;
    appearance: none;
    background: white url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="lightgray" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>') no-repeat right 10px center;
    background-size: 16px;
    color: var(--color-text-dark);
}

/* Placeholder/First Option Styling to mimic the image */
.dropdown-input option:first-child {
    color: var(--color-text-dark);
    font-weight: bold;
}

/* 6. "Add" Button */
.add-btn {
    background-color: #3e443e; /* Darker, sophisticated green/gray color */
    color: var(--color-white);
    padding: 8px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    float: right; /* Position it to the right */
    margin-top: 10px;
}

.add-btn:hover {
    background-color: #0f4f1d;
}
/* New style for the compact preview image */
.compact-preview {
    max-width: 75%; /* Only take up half the width of the container */
    max-height: 500px; /* Constrain the height */
    width: auto; /* Allow image to scale down */
    height: auto; 
    border: 1px solid #ddd; 
    cursor: zoom-in;
    /* Center the image within its container */
    display: block; 
    margin: 0 auto; 
}
/* --- New CSS for Full-View Modal (Lightbox) --- */

/* 1. The Modal Background */
.modal-full-view {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 200; /* Sit on top, above the address modal */
    padding-top: 60px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* 2. Modal Content (The Image) */
.modal-full-view-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    max-height: 80%;
    object-fit: contain; /* Ensure the image fits within the bounds */
}

/* 3. Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* 4. Close Button */
.close-full-view-btn {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close-full-view-btn:hover,
.close-full-view-btn:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* Add some animation for the image */
.modal-full-view-content, #caption {  
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}
/* ===============================
   MOBILE FIX – PAYMENT INFO
   =============================== */
@media (max-width: 768px) {
        /* Increase padding of summary box */
    .order-summary {
        padding: 25px 20px;
    }

    /* Make grid single-column on mobile */
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 18px;
    }

    /* Labels & values stacked */
    .summary-label,
    .summary-value {
        grid-column: 1 / -1;
        text-align: left;
    }

    .summary-value {
        font-size: 15px;
        margin-top: 4px;
    }

    /* Subtotal & delivery spacing */
    .subtotal-row,
    .delivery-row {
        padding-bottom: 10px;
    }

    /* Total amount spacing */
    .total-amount-row {
        padding-top: 15px;
        margin-top: 10px;
    }

    .total-amount-label {
        font-size: 15px;
    }

    .total-amount-value {
        font-size: 18px;
    }

    /* Payment method & address stacked */
    .payment-method-row {
        flex-direction: column;
        gap: 12px;
        padding-top: 15px;
    }

    .payment-method-details {
        align-items: center;
    }

    .address-value {
        max-width: 100%;
        text-align: left;
        margin-left: 0;
        line-height: 1.6;
    }
    /* Stack payment info & QR vertically */
    .top-payment-qr {
        flex-direction: column;
        align-items: stretch;
        margin-right: 0;
        padding-bottom: 15px;
        gap: 15px; /* controls spacing cleanly */
    }

    /* Payment info box full width */
    .kbz-pay-info {
        margin-right: 0;
        padding: 15px;
    }

    /* Center QR section */
    .qr-code-section {
        width: 100%;
        text-align: center;
    }

    /* Smaller QR for mobile */
    .qr-code-img {
        width: 130px;
        height: 130px;
    }

    /* Reduce spacing between label/value rows */
    .detail-row {
        grid-template-columns: 70px 1fr;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Reduce container padding overall */
    .payment-confirmation-section {
        padding: 0;
    }
}
    /* --- Mobile Responsiveness Fixes --- */
@media (max-width: 600px) {
    /* 1. Ensure the main container doesn't overflow */
    .checkout-page {
        padding: 10px; /* Reduce outer padding */
    }
    
    .checkout-page .main-container {
        width: 100% !important;
        box-sizing: border-box;
    }

    /* 2. Fix Address Detail Grid (Labels above Values) */
    .address-detail-grid {
        grid-template-columns: 1fr; /* Stack label and value */
        gap: 5px;
    }

    .addr-label {
        margin-top: 10px;
        font-size: 11px;
    }

    .addr-value {
        padding-left: 5px;
        word-break: break-word; /* Prevents long text/emails from pushing the container */
    }

    /* 3. Fix Form Grid (Stacking Input Fields) */
    .form-grid-2 {
        grid-template-columns: 1fr; /* One field per row */
        gap: 15px;
    }

    /* 4. Global Input Fix */
    .form-input, 
    .address-form-container input, 
    .address-form-container select, 
    .address-form-container textarea {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box; /* Crucial: includes padding in the 100% width */
    }

    /* 5. Adjust Header Spacing */
    .address-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .address-accordion-title {
        font-size: 14px;
        padding: 12px;
    }
}
@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}
/* --- Address List Styling --- */
.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.address-header h2 {
    margin: 0;
    color: var(--color-primary-dark);
}

.add-new-btn {
    background-color: var(--color-primary-dark);
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.address-item {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    margin-bottom: 12px;
    border-radius: 6px;
    overflow: hidden;
}

.address-accordion-title {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: #f9f9f9;
    font-weight: bold;
    color: var(--color-primary-dark);
    transition: background 0.2s;
}

.address-accordion-title:hover {
    background: var(--color-accent-green);
}

.address-content {
    padding: 20px;
    border-top: 1px solid #eee;
    display: none; /* Controlled by JS */
}

/* Detail Grid Layout */
.address-detail-grid {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 10px 20px;
    margin-bottom: 20px;
}

.addr-label {
    color: var(--color-text-light);
    font-size: 12px;
    text-transform: uppercase;
    font-weight: bold;
}

.addr-value {
    color: var(--color-text-dark);
    font-size: 14px;
    font-weight: 500;
}

.address-full-row {
    grid-column: 1 / -1;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #eee;
}

/* Address Form Styling */
.address-form-container {
    padding: 20px;
    background: #fcfcfc;
    border-radius: 8px;
    margin-top: 20px;
    display: none; /* Controlled by JS */
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* Action Buttons */
.address-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-green {
    background-color: var(--color-border-green);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-remove {
    background-color: #d9534f;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}
    </style>
</head>
<body>
    <div class='checkout-page'>
    <div class='main-container'>

        <div class='order-submit-section'>
            <h1>ORDER SUBMIT</h1>

            <div class='section-header'>PAYMENT METHOD</div>
            <div class='payment-options-list'>
                <label for='pay-bank' class='payment-option-label'> 
                    <input type='radio' id='pay-bank' name='high_level_payment' value='bank_transfer' checked>
                    Bank Transfer
                </label>
                <label for='pay-cod' class='payment-option-label'>
                    <input type='radio' id='pay-cod' name='high_level_payment' value='cod'>
                    Cash on delivery (COD)
                </label>
            </div>
            <div class='section-header'>DELIVERY ADDRESS</div>
            <div class='card'>
                <div class='address-header'>
                    <h2 style='font-size: 24px;'>ADDRESS LIST</h2>
                    <button class='add-new-btn' onclick='openNewAddressForm()'><i class='fas fa-plus'></i> Add New</button>
                </div>

                <div id='address-container'>
                </div>

                <div id='new-address-form-wrapper' class='address-form-container' style='border: 2px solid var(--rolex-green);'>
                    <h3 style='margin-bottom:15px;'>Add New Address</h3>
                    <div id='dynamic-new-form-fields'></div>
                    <div style='margin-top:15px; display:flex; gap:10px; justify-content:flex-end;'>
                        <button class='btn-remove' onclick='closeNewAddressForm()' style='background: #ccc; color: #333;'>Cancel</button>
                        <button class='btn-green' onclick='saveNewAddress()'>Save Address</button>
                    </div>
                </div>
            </div>
            <hr>

            <div id='detailedPaymentSection' class='detailed-payment-selection'>
                <div class='section-header'>SELECT PAYMENT METHOD</div>
                <div class='payment-methods'>

                    <label class='payment-card selected-card' data-logo='kpay.png' data-name='KBZ Pay'>
                        <div class='card-details'>
                            <img src='kpay.png' alt='KBZ Pay Logo' class='logo-img'>
                            <div class='text-info'>
                                <span class='type'>Mobile Wallet</span>
                                <span class='provider'>KBZ Pay</span>
                            </div>
                        </div>
                        <input type='radio' name='detailed_payment' value='kbz_pay' checked>
                        <span class='checkmark'></span>
                    </label>

                    <label class='payment-card' data-logo='kbz.png' data-name='KBZ Bank'>
                        <div class='card-details'>
                            <img src='kbz.png' alt='KBZ Bank Logo' class='logo-img'>
                            <div class='text-info'>
                                <span class='type'>Bank Transfer</span>
                                <span class='provider'>KBZ Bank</span>
                            </div>
                        </div>
                        <input type='radio' name='detailed_payment' value='kbz_bank'>
                        <span class='checkmark'></span>
                    </label>

                    <label class='payment-card' data-logo='uab.png' data-name='UAB Pay'>
                        <div class='card-details'>
                            <img src='uab.png' alt='UAB Pay Logo' class='logo-img'>
                            <div class='text-info'>
                                <span class='type'>Mobile Wallet</span>
                                <span class='provider'>UAB Pay</span>
                            </div>
                        </div>
                        <input type='radio' name='detailed_payment' value='uab_pay'>
                        <span class='checkmark'></span>
                    </label>

                    <label class='payment-card' data-logo='aya.png' data-name='AYA Pay'>
                        <div class='card-details'>
                            <img src='aya.png' alt='AYA Pay Logo' class='logo-img'>
                            <div class='text-info'>
                                <span class='type'>Mobile Wallet</span>
                                <span class='provider'>AYA Pay</span>
                            </div>
                        </div>
                        <input type='radio' name='detailed_payment' value='aya_pay'>
                        <span class='checkmark'></span>
                    </label>

                </div>

                <p class='security-disclaimer'>All payments are secured and encrypted</p>
            </div>
            </div>

        <div id='paymentConfirmationSection' class='payment-confirmation-section'>
            <div class='section-title'>PAYMENT INFORMATION</div>
            
            <div class='top-payment-qr'>
                <div class='kbz-pay-info'>
                    <div class='logo-row'>
                        <img src='kpay.png' alt='KBZ Pay Logo' class='kbz-logo' id='qr-logo-img'>
                        <div class='logo-text-info'>
                            <span class='mobile-wallet-text' id='qr-payment-type'>Mobile Wallet</span>
                            <span class='kbz-pay-provider' id='qr-payment-provider'>KBZ Pay</span>
                        </div>
                    </div>

                    <div class='detail-row'>
                        <span class='detail-label'>Ph no:</span>
                        <span class='detail-value' id='dynamic-phone'></span>
                    </div>
                    <div class='detail-row'>
                        <span class='detail-label'>Name:</span>
                        <span class='detail-value' id='dynamic-name'></span>
                    </div>
                    <div class='detail-row'>
                        <span class='detail-label'>Amount:</span>
                        <span class='detail-value' id='dynamic-amount'></span>
                    </div>
                </div>

                <div class='qr-code-section'>
                    <img src='qr.png' alt='QR Code' class='qr-code-img' id='dynamic-qr-img'> 
                    <div class='scan-here-text'>Scan Here</div>
                </div>
            </div>
            
            <div class='slip-policy-container'>
                <div class='payment-slip-upload'>
                    <div class='slip-header'>PAYMENT SLIP UPLOAD</div>
    
                    <div id='image-preview-container' style='display: none; margin-bottom: 15px; text-align: center;'>
                        <img id='slip-preview-img' src='#' alt='Payment Slip Preview' class='compact-preview'>
                    </div>

                    <div class='upload-section'>
                        <input type='file' id='upload-slip-input' accept='image/*' style='display: none;'>
                        <label for='upload-slip-input' class='action-btn upload-btn'>
                            <i class='fas fa-upload'></i> Upload Payment Slip Here
                        </label>
                        <p class='file-status' id='file-upload-status'>No file selected.</p>
                    </div>
                </div>

                <div class='policy-info'>
                    <div>
                        <h3 class='policy-header'>PAYMENT INFORMATION</h3>
                        <ul class='policy-list'>
                            <li>If you select **Bank Transfer**, full payment must be completed within 2 days of placing the order.</li>
                            <li>Orders not paid within 2 days will be automatically cancelled.</li>
                            <li>Your order will be confirmed and processed only after successful payment has been received and verified.</li>
                        </ul>
                        <p>Thank you for your attention to these payment terms.</p>

                        <h3 class='return-policy-header'>RETURN POLICY</h3>
                        <p class='return-policy-text'>To initiate a return for your order, please contact us via our Facebook Page Messenger or email, providing your **Order ID** for reference.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class='order-summary'>
            <div class='summary-header'>ORDER SUMMARY</div>

            <div class='summary-grid'>
                <div class='summary-item subtotal-row'>
                    <span class='summary-label'>Sub Total :</span>
                    <span class='summary-value'>550,000 MMK</span>
                </div>

                <div class='summary-item delivery-row'>
                    <span class='summary-label'>Delivery :</span>
                    <span class='summary-value'>3,500 MMK</span>
                </div>

                <div class='summary-item total-amount-row'>
                    <span class='total-amount-label'>Total Amount</span>
                    <span class='total-amount-value'>553,500 MMK</span>
                </div>

                <div class='summary-item payment-method-row'>
                    <div class='payment-method-details'>
                        <span class='summary-label' style='color: var(--color-text-dark); font-weight: normal;'>Payment Method :</span>
                        <span id='summary-payment-name'>KBZ Pay</span>
                        <img src='kpay.png' alt='Payment Method Logo' class='payment-method-logo' id='summary-payment-logo'>
                    </div>
                    <div>
                        <span class='summary-label' style='color: var(--color-text-dark); font-weight: normal;'>Address :</span>
                        <p class='address-value'>Yadanar Street, Chanayethazan Township, Mandalay, Mandalay Region</p>
                    </div>
                </div>
            </div>
        </div>
        
        <button class='submit-btn'>SUBMIT ORDER</button>
    </div>

    <div id='newAddressModal' class='modal'>
        <div class='modal-content'>
            <span class='close-btn'>&times;</span>
            <h2 class='modal-header'>NEW ADDRESS</h2>
            
            <form id='newAddressForm'>
                <div class='input-group'>
                    <label for='street'>Street:</label>
                    <select id='street' name='street' class='dropdown-input'>
                        <option value=''>Select Street</option>
                        <option value='yadanar'>Yadanar Street</option>
                    </select>
                </div>

                <div class='input-group'>
                    <label for='township'>Township:</label>
                    <select id='township' name='township' class='dropdown-input'>
                        <option value=''>Select Township</option>
                        <option value='chanayethazan'>Chanayethazan</option>
                    </select>
                </div>

                <div class='input-group'>
                    <label for='city'>City :</label>
                    <select id='city' name='city' class='dropdown-input'>
                        <option value=''>Select City</option>
                        <option value='mandalay'>Mandalay</option>
                    </select>
                </div>

                <div class='input-group'>
                    <label for='region'>State/Region:</label>
                    <select id='region' name='region' class='dropdown-input'>
                        <option value=''>Select Region</option>
                        <option value='mandalay_region'>Mandalay Region</option>
                    </select>
                </div>
                
                <button type='submit' class='add-btn'>Add</button>
            </form>
        </div>
    </div>

    <div id='fullViewModal' class='modal-full-view'>
        <span class='close-full-view-btn'>&times;</span>
        <img class='modal-full-view-content' id='img-full-view'>
        <div id='caption'>Payment Slip</div>
    </div>
</div>
</body>

<script>
    // --- New Payment Data Object for Dynamic Updates ---
    const paymentDetails = {
        'kbz_pay': { // Matches input[value]
            name: 'U Aung Aung',
            phone: '09-123 456 789',
            amount: '553,500 MMK',
            qrCodeSrc: 'qr.png' // Placeholder QR image
        },
        'kbz_bank': {
            name: 'Daw Mya Mya',
            phone: '001-234-567-890 (Account No)',
            amount: '553,500 MMK',
            qrCodeSrc: 'qr_kbz_bank.png' // Placeholder QR image
        },
        'uab_pay': {
            name: 'U Hla Tun',
            phone: '09-444 555 666',
            amount: '553,500 MMK',
            qrCodeSrc: 'qr_uab.png' // Placeholder QR image
        },
        'aya_pay': {
            name: 'Ma Thidar',
            phone: '09-777 888 999',
            amount: '553,500 MMK',
            qrCodeSrc: 'qr_aya.png' // Placeholder QR image
        }
    };

    // New: Function to setup listeners for camera and file upload
    // New: Function to setup listeners for camera and file upload
function setupFileInputListeners() {
    // --- Elements for Preview & Status ---
    const fileStatus = document.getElementById('file-upload-status');
    const uploadSlipInput = document.getElementById('upload-slip-input');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImg = document.getElementById('slip-preview-img');

    // --- Elements for Full-View Modal ---
    const fullModal = document.getElementById('fullViewModal');
    const fullModalImg = document.getElementById('img-full-view');
    const fullModalCloseBtn = document.querySelector('.close-full-view-btn');

    fileStatus.textContent = 'No file selected.';
    fileStatus.style.color = 'var(--color-text-light)';

    // 1. Listener for the File Input Change (Handles Preview)
    uploadSlipInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileStatus.textContent = `Uploaded file: ${file.name}. Click to view full image.`;
            fileStatus.style.color = 'var(--color-primary-dark)';
            
            // Create a temporary URL for the image preview
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                previewContainer.style.display = 'block'; // Show the preview container
            };
            reader.readAsDataURL(file);

        } else {
            fileStatus.textContent = 'No file selected.';
            fileStatus.style.color = 'var(--color-text-light)';
            previewContainer.style.display = 'none'; // Hide the preview container
            previewImg.src = '#';
        }
    });

    // 2. Listener for clicking the preview image (Handles Full View)
    previewImg.addEventListener('click', () => {
        if (previewImg.src && previewImg.src !== '#') {
            fullModal.style.display = 'block';
            fullModalImg.src = previewImg.src;
        }
    });

    // 3. Close Full-View Modal
    fullModalCloseBtn.onclick = function() {
        fullModal.style.display = 'none';
    }

    // Close Full-View Modal when clicking anywhere outside the image
    window.addEventListener('click', (event) => {
        // Only close the full-view modal, not the address modal
        if (event.target === fullModal) {
            fullModal.style.display = 'none';
        }
    });
}


    document.addEventListener('DOMContentLoaded', (event) => {
        // --- DOM Elements ---
        const highLevelPaymentRadios = document.querySelectorAll('input[name="high_level_payment"]');
        const highLevelPaymentLabels = document.querySelectorAll('.payment-option-label');
        const detailedPaymentSection = document.getElementById('detailedPaymentSection');
        const paymentConfirmationSection = document.getElementById('paymentConfirmationSection');
        const detailedPaymentRadios = document.querySelectorAll('input[name="detailed_payment"]');

        const summaryPaymentName = document.getElementById('summary-payment-name');
        const summaryPaymentLogo = document.getElementById('summary-payment-logo');
        
        const qrLogoImg = document.getElementById('qr-logo-img');
        const qrPaymentType = document.getElementById('qr-payment-type');
        const qrPaymentProvider = document.getElementById('qr-payment-provider');
        
        // Dynamic Payment Info Elements (New IDs)
        const dynamicPhone = document.getElementById('dynamic-phone');
        const dynamicName = document.getElementById('dynamic-name');
        const dynamicAmount = document.getElementById('dynamic-amount');
        const dynamicQrImg = document.getElementById('dynamic-qr-img');
        
        // --- Functions ---
        
        // 1. Update the display for Bank Transfer / COD
        function updateDisplay(paymentMethod) {
    const isBankTransfer = paymentMethod === 'bank_transfer';

    // Toggle visibility of Bank Transfer sections
    detailedPaymentSection.style.display = isBankTransfer ? 'block' : 'none';
    paymentConfirmationSection.style.display = isBankTransfer ? 'block' : 'none';

    // --- IMPROVED LOGIC FOR ACTIVE CLASS ---
    // Instead of mapping strings, just check which radio is actually checked
    highLevelPaymentLabels.forEach(label => {
        const radioInside = label.querySelector('input[name="high_level_payment"]');
        if (radioInside && radioInside.checked) {
            label.classList.add('selected');
        } else {
            label.classList.remove('selected');
        }
    });

    // Update Order Summary & Dynamic Info
    if (isBankTransfer) {
        const checkedDetailedCard = document.querySelector('input[name="detailed_payment"]:checked');
        if (checkedDetailedCard) {
            updateDetailedPaymentSummary(checkedDetailedCard);
        }
    } else { // COD
        summaryPaymentName.textContent = 'Cash on Delivery';
        summaryPaymentLogo.src = ''; 
        summaryPaymentLogo.alt = '';
    }
}
   // --- Initial Load ---
const checkedRadio = document.querySelector('input[name="high_level_payment"]:checked');
if (checkedRadio) {
    // Calling the function ensures everything is in sync on page load
    updateDisplay(checkedRadio.value);
}     
        // 2. Update the Order Summary and QR/Card details based on the selected detailed payment option
        function updateDetailedPaymentSummary(radioElement) {
            const cardLabel = radioElement.closest('.payment-card');
            const paymentId = radioElement.value; // Get the ID from the radio value
            const paymentName = cardLabel.getAttribute('data-name');
            const logoSrc = cardLabel.getAttribute('data-logo');
            const paymentType = cardLabel.querySelector('.type').textContent;
            
            const details = paymentDetails[paymentId]; // Get the dynamic details

            // Update Order Summary
            summaryPaymentName.textContent = paymentName;
            summaryPaymentLogo.src = logoSrc;
            summaryPaymentLogo.alt = paymentName + ' Logo';

            // Update QR/Card Details (using the confirmation section)
            qrLogoImg.src = logoSrc;
            qrLogoImg.alt = paymentName + ' Logo';
            qrPaymentProvider.textContent = paymentName;
            qrPaymentType.textContent = paymentType;
            
            // Update Dynamic Payment Info (Ph no, Name, Amount, QR Code)
            if (details) {
                dynamicPhone.textContent = details.phone;
                dynamicName.textContent = details.name;
                dynamicAmount.textContent = details.amount;
                dynamicQrImg.src = details.qrCodeSrc;
            }

            // Update selected class for detailed cards
            document.querySelectorAll('.payment-card').forEach(card => card.classList.remove('selected-card'));
            cardLabel.classList.add('selected-card');
            
            // Reset file upload status on payment method change
            document.getElementById('file-upload-status').textContent = 'No file selected.';
        }

        // --- Event Listeners ---

        // 1. High-Level Payment Listener (Bank Transfer / COD)
        highLevelPaymentRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                updateDisplay(e.target.value);
            });
        });

        // 2. Detailed Payment Listener (KBZ Pay, KBZ Bank, etc.)
        detailedPaymentRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                updateDetailedPaymentSummary(e.target);
            });
        });

        // 3. Modal Logic (kept from original)
        const modal = document.getElementById('newAddressModal');
        const openBtn = document.querySelector('.add-new-address-btn');
        const closeBtn = document.querySelector('.close-btn');
        
        openBtn.onclick = function() { modal.style.display = 'block'; }
        closeBtn.onclick = function() { modal.style.display = 'none'; }
        window.onclick = function(event) { if (event.target == modal) { modal.style.display = 'none'; } }

        document.getElementById('newAddressForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert("Address added (simulated)");
            modal.style.display = 'none';
        });

        // 4. Setup File Upload Listeners
        setupFileInputListeners();


        // --- Initial Load ---
        // Ensure the initial state reflects "Bank Transfer" (which is checked by default)
        const initialPayment = document.querySelector('input[name="high_level_payment"]:checked').value;
        updateDisplay(initialPayment);
    });
    
        const myanmarCities = [
            {'name_en': 'Yangon'}, {'name_en': 'Mandalay'}, {'name_en': 'Nay Pyi Taw'}, {'name_en': 'Mawlamyine'}, {'name_en': 'Bago'},
            {'name_en': 'Pathein'}, {'name_en': 'Monywa'}, {'name_en': 'Meiktila'}, {'name_en': 'Taunggyi'}, {'name_en': 'Myitkyina'},
            {'name_en': 'Lashio'}, {'name_en': 'Sittwe'}, {'name_en': 'Pyay'}, {'name_en': 'Hinthada'}, {'name_en': 'Magway'},
            {'name_en': 'Myeik'}, {'name_en': 'Taungoo'}, {'name_en': 'Myingyan'}, {'name_en': 'Dawei'}, {'name_en': 'Pakokku'},
            {'name_en': 'Pyin Oo Lwin'}, {'name_en': 'Hpa-An'}, {'name_en': 'Kyaukse'}, {'name_en': 'Shwebo'}, {'name_en': 'Sagaing'},
            {'name_en': 'Tachileik'}, {'name_en': 'Hakha'}, {'name_en': 'Loikaw'}, {'name_en': 'Kengtung'}, {'name_en': 'Thanlyin'},
            {'name_en': 'Twantay'}, {'name_en': 'Kyauktan'}, {'name_en': 'Bogale'}, {'name_en': 'Pyapon'}, {'name_en': 'Kyaiklat'},
            {'name_en': 'Maubin'}, {'name_en': 'Nyaungdon'}, {'name_en': 'Dedaye'}, {'name_en': 'Kyaukpyu'}, {'name_en': 'Thandwe'},
            {'name_en': 'Toungup'}, {'name_en': 'Gwa'}, {'name_en': 'Manaung'}, {'name_en': 'Kyeintali'}, {'name_en': 'Minbya'},
            {'name_en': 'Mrauk-U'}, {'name_en': 'Pauktaw'}, {'name_en': 'Myebon'}, {'name_en': 'Ann'}, {'name_en': 'Buthidaung'},
            {'name_en': 'Maungdaw'}, {'name_en': 'Kyauktaw'}, {'name_en': 'Ponnagyun'}, {'name_en': 'Rathedaung'}, {'name_en': 'Kawthaung'},
            {'name_en': 'Bokpyin'}, {'name_en': 'Yebyu'}, {'name_en': 'Launglon'}, {'name_en': 'Thayetchaung'}, {'name_en': 'Tanintharyi'},
            {'name_en': 'Kyunsu'}, {'name_en': 'Myitta'}, {'name_en': 'Kawkareik'}, {'name_en': 'Myawaddy'}, {'name_en': 'Kyeikdon'},
            {'name_en': 'Kyeikmaraw'}, {'name_en': 'Hlaingbwe'} , {'name_en' : 'Other'}
        ];

        const thailandCities = [
            {'name_en': 'Bangkok'}, {'name_en': 'Samut Prakan'}, {'name_en': 'Nonthaburi'}, {'name_en': 'Pathum Thani'}, {'name_en': 'Phra Nakhon Si Ayutthaya'},
            {'name_en': 'Ang Thong'}, {'name_en': 'Loburi'}, {'name_en': 'Sing Buri'}, {'name_en': 'Chai Nat'}, {'name_en': 'Saraburi'},
            {'name_en': 'Chon Buri'}, {'name_en': 'Rayong'}, {'name_en': 'Chanthaburi'}, {'name_en': 'Trat'}, {'name_en': 'Chachoengsao'},
            {'name_en': 'Prachin Buri'}, {'name_en': 'Nakhon Nayok'}, {'name_en': 'Sa Kaeo'}, {'name_en': 'Nakhon Ratchasima'}, {'name_en': 'Buri Ram'},
            {'name_en': 'Surin'}, {'name_en': 'Si Sa Ket'}, {'name_en': 'Ubon Ratchathani'}, {'name_en': 'Yasothon'}, {'name_en': 'Chaiyaphum'},
            {'name_en': 'Amnat Charoen'}, {'name_en': 'Bueng Kan'}, {'name_en': 'Nong Bua Lam Phu'}, {'name_en': 'Khon Kaen'}, {'name_en': 'Udon Thani'},
            {'name_en': 'Loei'}, {'name_en': 'Nong Khai'}, {'name_en': 'Maha Sarakham'}, {'name_en': 'Roi Et'}, {'name_en': 'Kalasin'},
            {'name_en': 'Sakon Nakhon'}, {'name_en': 'Nakhon Phanom'}, {'name_en': 'Mukdahan'}, {'name_en': 'Chiang Mai'}, {'name_en': 'Lamphun'},
            {'name_en': 'Lampang'}, {'name_en': 'Uttaradit'}, {'name_en': 'Phrae'}, {'name_en': 'Nan'}, {'name_en': 'Phayao'},
            {'name_en': 'Chiang Rai'}, {'name_en': 'Mae Hong Son'}, {'name_en': 'Nakhon Sawan'}, {'name_en': 'Uthai Thani'}, {'name_en': 'Kamphaeng Phet'},
            {'name_en': 'Tak'}, {'name_en': 'Sukhothai'}, {'name_en': 'Phitsanulok'}, {'name_en': 'Phichit'}, {'name_en': 'Phetchabun'},
            {'name_en': 'Ratchaburi'}, {'name_en': 'Kanchanaburi'}, {'name_en': 'Suphan Buri'}, {'name_en': 'Nakhon Pathom'}, {'name_en': 'Samut Sakhon'},
            {'name_en': 'Samut Songkhram'}, {'name_en': 'Phetchaburi'}, {'name_en': 'Prachuap Khiri Khan'}, {'name_en': 'Nakhon Si Thammarat'}, {'name_en': 'Krabi'},
            {'name_en': 'Phangnga'}, {'name_en': 'Phuket'}, {'name_en': 'Surat Thani'}, {'name_en': 'Ranong'}, {'name_en': 'Chumphon'},
            {'name_en': 'Songkhla'}, {'name_en': 'Satun'}, {'name_en': 'Trang'}, {'name_en': 'Phatthalung'}, {'name_en': 'Pattani'},
            {'name_en': 'Yala'}, {'name_en': 'Narathiwat'}, {'name_en' : 'Other'}
        ];
        
         let addressData = [
            { id: 1, isCurrent: true, street: 'Yadanar Street', township: 'Chanayethazan', city: 'Mandalay', state: 'Mandalay', country: 'Myanmar', postal: '1001', map: 'maplink1', fullAddress: 'Yadanar Street, Chanayethazan Township, Mandalay, Mandalay Region'},
            { id: 2, isCurrent: false, street: 'Second Road', township: 'Bauktaw', city: 'Yangon', state: 'Yangon', country: 'Myanmar', postal: '1002', map: 'maplink2', fullAddress: 'Second Road, Bauktaw Township, Yangon, Yangon Region'},
        ];
        
        let nextAddressId = 3;

        const addressFormTemplate = (addr) => `
                <div class='form-grid-2'>
                    <div class='form-group'>
                        <label class='label'>Street</label>
                        <input type='text' class='form-input addr-street' placeholder='Street Address' value='${addr?.street || ''}'>
                    </div>
                    <div class='form-group'>
                        <label class='label'>Township</label>
                        <input type='text' class='form-input addr-township' placeholder='Township' value='${addr?.township || ''}'>
                    </div>
                </div>
                <div class='form-grid-2'>
                        <div class='form-group'>
                        <label class='label'>State</label>
                        <input type='text' class='form-input addr-state' placeholder='State/Province' value='${addr?.state || ''}'>
                    </div>
                    <div class='form-group'>
                        <label class='label'>Postal Code</label>
                        <input type='text' class='form-input addr-postal' placeholder='Postal Code' value='${addr?.postal || ''}'>
                    </div>
                </div>
                <div class='form-grid-2'>
                    <div class='form-group'>
                        <label class='label'>Country</label>
                        <select class='form-input addr-country' onchange='updateCities(this)' data-selected='${addr?.country || ''}'>
                            <option value=''>Select Country</option>
                            <option value='Myanmar'>Myanmar</option>
                            <option value='Thailand'>Thailand</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label class='label'>City</label>
                        <select class='form-input addr-city' data-selected='${addr?.city || ''}'>
                            <option value=''>Select City</option>
                        </select>
                    </div>
                </div>
                <div class='form-group' style='margin-top:15px;'>
                    <label class='label'>Complete Address</label>
                    <textarea class='form-input addr-complete' rows='2' placeholder='Full Address string'>${addr?.fullAddress || ''}</textarea>
                </div>
                <div class='form-group' style='margin-top:15px;'>
                    <label class='label'>Google Map Link</label>
                    <input type='text' class='form-input addr-map' value='${addr?.map || ''}'>
                </div>
            `;
        
        function updateCities(countrySelect, cityValue = '') {
            const row = countrySelect.closest('.address-form-container') || countrySelect.closest('.form-grid-2').parentNode;
            const citySelect = row.querySelector('.addr-city');
            const country = countrySelect.value;
            
           citySelect.innerHTML = '<option value="">Select City</option>';

            let cities = [];
            if (country === 'Myanmar') {
                cities = myanmarCities.map(c => c.name_en);
            } else if (country === 'Thailand') {
                cities = thailandCities.map(c => c.name_en);
            }

            cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                if (city === cityValue) {
                    opt.selected = true;
                }
                citySelect.appendChild(opt);
            });
        }
        
        function loadCountryCity(formContainer, countryValue, cityValue) {
            const countrySelect = formContainer.querySelector('.addr-country');
            countrySelect.value = countryValue;
            updateCities(countrySelect, cityValue);
        }

        function toggleAddress(id) {
            const content = document.getElementById(`addr-content-${id}`);
            const chevron = document.querySelector(`.address-accordion-title[data-id='${id}'] i`);
            
            const isClosing = content.style.display === 'block';

            document.querySelectorAll('.address-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.address-form-container').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.address-accordion-title i').forEach(i => i.className = 'fas fa-chevron-right');
            closeNewAddressForm();

            if (isClosing) {
                content.style.display = 'none';
                chevron.className = 'fas fa-chevron-right';
            } else {
                content.style.display = 'block';
                chevron.className = 'fas fa-chevron-down';
            }
        }

        function editAddress(id) {
            const addr = addressData.find(a => a.id === id);
            if (!addr) return;
            
            document.getElementById(`addr-content-${id}`).style.display = 'none';
            document.querySelectorAll('.address-form-container').forEach(el => el.style.display = 'none');
            closeNewAddressForm();

            const container = document.getElementById(`edit-form-container-${id}`);
            container.innerHTML = `
                ${addressFormTemplate(addr)}
                <div style='margin-top:15px; text-align:right;'>
                    <button class='btn-green' onclick='saveAddress(${id}, 'edit')'>Save Changes</button>
                    <button class='btn-remove' style='background:#ccc; color:#333;' onclick='closeEdit(${id})'>Cancel</button>
                </div>
            `;
            container.style.display = 'block';
            loadCountryCity(container, addr.country, addr.city);
        }

        function closeEdit(id) {
            document.getElementById(`edit-form-container-${id}`).style.display = 'none';
            toggleAddress(id); 
            renderAddresses();
        }

        function closeNewAddressForm() {
             document.getElementById('new-address-form-wrapper').style.display = 'none';
        }

        function openNewAddressForm() {
            document.querySelectorAll('.address-form-container').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.address-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.address-accordion-title i').forEach(i => i.className = 'fas fa-chevron-right');

            const container = document.getElementById('dynamic-new-form-fields');
            container.innerHTML = addressFormTemplate(null); 
            document.getElementById('new-address-form-wrapper').style.display = 'block';
        }
        function renderAddresses() {
    const container = document.getElementById('address-container');
    const summaryAddressDisplay = document.querySelector('.address-value');
    container.innerHTML = '';

    addressData.forEach((addr, index) => {
        const addressNumber = index + 1;
        const item = document.createElement('div');
        const isSelected = summaryAddressDisplay.textContent.includes(addr.street);
        item.className = `address-item ${isSelected ? 'selected-address' : ''}`;
        
        const contentEl = document.getElementById(`addr-content-${addr.id}`);
        const isExpanded = contentEl && contentEl.style.display === 'block';
        const chevronClass = isExpanded ? 'fa-chevron-down' : 'fa-chevron-right';
        const contentDisplay = isExpanded ? 'block' : 'none';

        item.innerHTML = `
            <div class='address-accordion-title' onclick='toggleAddress(${addr.id})' data-id='${addr.id}'>
                <span>
                    <i class='fas ${chevronClass}'></i> 
                    ADDRESS ${addressNumber}
                </span>
                ${isSelected ? '<span class="selected-badge" style="color: var(--color-border-green); font-size: 12px;"><i class="fas fa-check-circle"></i> SELECTED</span>' : ''}
            </div>
            
            <div class='address-content' id='addr-content-${addr.id}' style='display: ${contentDisplay};'>
                <div class='address-detail-grid'>
                    <div class='addr-label'>Street</div><div class='addr-value'>${addr.street || '-'}</div>
                    <div class='addr-label'>Township</div><div class='addr-value'>${addr.township || '-'}</div>
                    <div class='addr-label'>City</div><div class='addr-value'>${addr.city || '-'}</div>
                    <div class='address-full-row'>
                        <div class='addr-label'>Full Address</div>
                        <div class='addr-value'>${addr.fullAddress || '-'}</div>
                    </div>
                </div>
                
                <div class='address-actions' style="justify-content: space-between; align-items: center;">
                    <button class='btn-green' style="background-color: var(--color-primary-dark);" 
                            onclick='selectAddress(${addr.id})'>Deliver to this Address</button>
                    <div>
                        <button class='btn-green' onclick='editAddress(${addr.id})'>Edit</button>
                        <button class='btn-remove' onclick='removeAddress(${addr.id})'>Remove</button>
                    </div>
                </div>
            </div>
            <div id='edit-form-container-${addr.id}' class='address-form-container'></div>
        `;
        container.appendChild(item);
    });
}
    function selectAddress(id) {
    const selectedAddr = addressData.find(a => a.id === id);
    if (selectedAddr) {
        const summaryAddress = document.querySelector('.address-value');
        summaryAddress.textContent = selectedAddr.fullAddress;

        console.log("Delivery address updated to: " + selectedAddr.fullAddress);
        
        renderAddresses();
    }
}
        document.addEventListener('DOMContentLoaded', () => {
            renderAddresses();
        });
</script>
</html>