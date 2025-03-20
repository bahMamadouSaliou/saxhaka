<?php

use Carbon\Carbon;
use App\Models\Users;
use App\Mail\Sendmail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\AppSetting;
use App\Models\ProductVat;
use App\Models\EmailConfig;
use Illuminate\Support\Facades\Mail;

// Protection contre la redéclaration de la fonction
if (!function_exists('MailSend')) {

    /**
     * @throws Exception
     */
    function MailSend($saleData, $product = null, $vatId = null, $receiverEmail = null): void
    { 
        $emailConfig = EmailConfig::first();

        if (!$emailConfig->emailConfigName) {
            throw new Exception("Email config name is not set");
        }

        // Configuration SMTP dynamique
        config([
            'mail.mailers.smtp.host' => $emailConfig->emailHost,
            'mail.mailers.smtp.port' => $emailConfig->emailPort,
            'mail.mailers.smtp.encryption' => $emailConfig->emailEncryption,
            'mail.mailers.smtp.username' => $emailConfig->emailUser,
            'mail.mailers.smtp.password' => $emailConfig->emailPass,
            'mail.mailers.smtp.local_domain' => env('MAIL_EHLO_DOMAIN'),
            'mail.from.address' => $emailConfig->emailUser,
            'mail.from.name' => $emailConfig->emailConfigName,
        ]);

        // Gestion du client
        if (isset($saleData['customerId'])) {
            $customer = Customer::find($saleData['customerId']);
            if (!$customer) throw new Exception("Customer not found");
            $receiverEmail ??= $customer->email;
        }

        // Récupération utilisateur
        $user = isset($saleData['userId']) ? Users::find($saleData['userId']) : null;

        // Calculs produits
        $productIds = array_column($product, 'productId');
        $productInfo = Product::whereIn('id', $productIds)->get();

        if ($vatId) {
            $subTotal = array_map(function($item) use ($product) {
                $quantity = $product[$item['id']]['productQuantity'] ?? 0;
                return $quantity * $item->productSalePrice * (1 + $item->productVat / 100);
            }, $productInfo->all());
        } else {
            $total = 0;
            $productQuantities = array_map(function($item) use (&$total, $product) {
                $quantity = $product[$item['id']]['productQuantity'] ?? 0;
                $total += $item->productSalePrice * $quantity;
                return $quantity;
            }, $productInfo->all());
        }

        // Construction des données
        $data = $vatId ? [
            'title' => 'Invoice',
            'invoiceId' => $saleData['id'],
            'customerName' => $customer->username ?? '',
            'customerEmail' => $customer->email ?? '',
            'customerAddress' => $customer->address ?? '',
            'productNames' => $productInfo->pluck('name')->toArray(),
            'productQuantities' => array_column($product, 'productQuantity'),
            'productPrices' => $productInfo->pluck('productSalePrice')->toArray(),
            'productVats' => $productInfo->pluck('productVat')->toArray(),
            'subtotal' => $subTotal ?? [],
            'govTax' => ProductVat::find($vatId)?->toArray() ?? [],
            'totalAmount' => $saleData['totalAmount'],
            'company' => AppSetting::first(),
            'discountAmount' => $saleData['discount'],
            'note' => $saleData['note'],
            'invoiceDate' => $saleData['date'],
            'dueAmount' => $saleData['dueAmount'],
            'paidAmount' => $saleData['paidAmount'],
            'salePerson' => $user->username ?? '',
        ] : [
            'title' => 'quote',
            'quoteId' => $saleData['id'],
            'quoteName' => $saleData['quoteName'],
            'quoteDate' => $saleData['quoteDate'],
            'quoteOwner' => $saleData['quoteOwner']['username'] ?? '',
            'expirationDate' => $saleData['expirationDate'],
            'productQuantities' => $productQuantities ?? [],
            'productPrices' => $productInfo->pluck('productSalePrice')->toArray(),
            'productNames' => $productInfo->pluck('name')->toArray(),
            'totalAmount' => $total ?? 0,
            'company' => AppSetting::first(),
            'termsAndConditions' => $saleData['termsAndConditions'],
        ];

        // Envoi de l'email (décommenter pour activer)
        // $email = Mail::to($receiverEmail)->send(new Sendmail($data));
        // if (!$email) throw new Exception("Email not sent");
    }
}

if (!function_exists('MailForReorder')) {

    /**
     * @throws Exception
     */
    function MailForReorder($reorderData, $productIds, $receiverEmail): void
    {
        $emailConfig = EmailConfig::first();
        if (!$emailConfig->emailConfigName) throw new Exception("Email config name is not set");

        config([
            'mail.mailers.smtp.host' => $emailConfig->emailHost,
            'mail.mailers.smtp.port' => $emailConfig->emailPort,
            'mail.mailers.smtp.encryption' => $emailConfig->emailEncryption,
            'mail.mailers.smtp.username' => $emailConfig->emailUser,
            'mail.mailers.smtp.password' => $emailConfig->emailPass,
            'mail.mailers.smtp.local_domain' => env('MAIL_EHLO_DOMAIN'),
            'mail.from.address' => $emailConfig->emailUser,
            'mail.from.name' => $emailConfig->emailConfigName,
        ]);

        $productInfo = Product::whereIn('id', $productIds)->get();
        $productquantities = array_column($reorderData, 'productQuantity');

        $email = Mail::to($receiverEmail)->send(new Sendmail([
            'title' => 'Purchase Reorder Invoice',
            'reorderId' => $reorderData[0]['reorderInvoiceId'] ?? '',
            'date' => Carbon::now()->format('Y-m-d'),
            'productQuantities' => $productquantities,
            'productNames' => $productInfo->pluck('name')->toArray(),
            'company' => AppSetting::first(),
        ]));

        if (!$email) throw new Exception("Email not sent");
    }
}