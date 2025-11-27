<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\JobCardModel;

/**
 * Payment Controller
 * 
 * Manages job payments.
 */
class PaymentController extends BaseController
{
    protected PaymentModel $paymentModel;
    protected JobCardModel $jobModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->jobModel = new JobCardModel();
    }

    /**
     * List all payments
     */
    public function index(): string
    {
        $payments = $this->paymentModel
            ->select('payments.*, job_cards.job_id, customers.name as customer_name')
            ->join('job_cards', 'job_cards.id = payments.job_card_id')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->orderBy('payments.payment_date', 'DESC')
            ->findAll();

        return view('payments/index', $this->getViewData([
            'title'    => lang('App.payment'),
            'payments' => $payments,
        ]));
    }

    /**
     * Store new payment
     */
    public function store()
    {
        $jobId = $this->request->getPost('job_card_id');
        
        $job = $this->jobModel->find($jobId);

        if (!$job) {
            return $this->errorResponse(lang('App.recordNotFound'), 404);
        }

        if ($job['is_locked']) {
            return $this->errorResponse('Job is locked', 403);
        }

        $rules = [
            'payment_method' => 'required|in_list[cash,transfer]',
            'amount'         => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->errorResponse(lang('App.operationFailed'), 400, $this->validator->getErrors());
        }

        $amount = (float) $this->request->getPost('amount');
        $method = $this->request->getPost('payment_method');
        $reference = $this->request->getPost('reference_number');

        if ($this->paymentModel->recordPayment($jobId, $method, $amount, $reference, $this->getUserId())) {
            return $this->successResponse(lang('App.paymentReceived'));
        }

        return $this->errorResponse(lang('App.operationFailed'));
    }

    /**
     * Get payments for a job (AJAX)
     */
    public function byJob(int $jobId)
    {
        $payments = $this->paymentModel->getByJob($jobId);
        $total = $this->paymentModel->getTotalPaidForJob($jobId);

        return $this->successResponse('Payments retrieved', [
            'payments' => $payments,
            'total'    => $total,
        ]);
    }
}

