<?php

namespace App\Console\Commands;

use App\Infrastructure\Persistence\Models\Payment;
use App\Jobs\SolutionPaymentJob;
use Illuminate\Console\Command;

class TransactionsConsult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transactions-consult';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::where('status', 'pending')->get();
        foreach ($payments as $payment) {
            SolutionPaymentJob::dispatch($payment);
        }
    }
}
