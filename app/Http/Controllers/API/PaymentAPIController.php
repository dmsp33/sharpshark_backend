<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePaymentAPIRequest;
use App\Http\Requests\API\UpdatePaymentAPIRequest;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\QuickPay;
use Response;

/**
 * Class PaymentController
 * @package App\Http\Controllers\API
 */

class PaymentAPIController extends AppBaseController
{
    /** @var  quickpayModel */
    private $quickpayModel;

    public function __construct(QuickPay $quickpayModel)
    {
        $this->quickpayModel = $quickpayModel;
    }


    
    public function payment(Request $request)
    {
        $input = $request->all();

        $payment = $this->quickpayModel->charge($request->user(), $input['amount'] ?? 100);

        return $this->sendResponse($payment, 'Payment saved successfully');
    }
}
