<?php

namespace Tests\Feature\Payments;

use App\Constants\PaymentStatus;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\Jobs\Payment\SolutionPaymentJob;
use App\PaymentGateway\PlacetopayGateway;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PlacetoPayPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSuccessfulPay()
    {
        $microsite = Microsite::factory()->create();

        $data = [
            'currency' => 'COP',
            'amount' => 10000,
            'description' => 'Test Payment',
            'payer_document' => '12345678',
            'payer_email' => 'test@example.com',
            'microsite_id' => $microsite->id
        ];
        $mockPlacetoPay = Mockery::mock(PlacetoPay::class);

        $mockPlacetoPay->shouldReceive('request')
            ->andReturn(new RedirectResponse($this->getCreateSession('successful')));

        $mockGateway = Mockery::mock(PlacetopayGateway::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mockGateway->shouldAllowMockingProtectedMethods();
        $mockGateway->shouldReceive('getPaymentPlacetoPay')
            ->andReturn($mockPlacetoPay);

        $this->app->instance(PlacetopayGateway::class, $mockGateway);

        $response = $this->post(route('payments.store', $microsite->id), $data);

        $response->assertStatus(302);
        $response->assertRedirect('https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a');

        $this->assertDatabaseHas('payments', [
            'microsite_id' => $microsite->id,
            'amount' => 10000,
            'status' => 'pending',
            'request_id' => 1,
        ]);
    }

    public function testFailedPay()
    {
        $microsite = Microsite::factory()->create();

        $data = [
            'currency' => 'COP',
            'amount' => 10000,
            'description' => 'Test Payment',
            'payer_document' => '12345678',
            'payer_email' => 'test@example.com',
            'microsite_id' => $microsite->id
        ];

        $mockPlacetoPay = Mockery::mock(PlacetoPay::class);

        $mockPlacetoPay->shouldReceive('request')
            ->andReturn(new RedirectResponse($this->getCreateSession('failed')));

        $mockGateway = Mockery::mock(PlacetopayGateway::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mockGateway->shouldAllowMockingProtectedMethods();
        $mockGateway->shouldReceive('getPaymentPlacetoPay')
            ->andReturn($mockPlacetoPay);

        $this->app->instance(PlacetopayGateway::class, $mockGateway);

        $response = $this->post(route('payments.store', $microsite->id), $data);

        $response->assertStatus(500);

        $this->assertDatabaseHas('payments', [
            'microsite_id' => $microsite->id,
            'amount' => 10000,
            'status' => 'rejected',
        ]);
    }


    public function testResolvePaymentJobApproved()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'request_id' => 1
        ]);

        $mockPlacetoPay = Mockery::mock(PlacetoPay::class);

        $mockPlacetoPay->shouldReceive('query')
            ->andReturn(new RedirectInformation($this->getQuery('approved')));

        $mockGateway = Mockery::mock(PlacetopayGateway::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mockGateway->shouldAllowMockingProtectedMethods();
        $mockGateway->shouldReceive('getPaymentPlacetoPay')
            ->andReturn($mockPlacetoPay);

        $this->app->instance(PlacetopayGateway::class, $mockGateway);

        $job = new SolutionPaymentJob($payment);
        $job->handle();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::APPROVED->value,
        ]);
    }


    public function testResolvePaymentJobRejected()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'request_id' => 1
        ]);

        $mockPlacetoPay = Mockery::mock(PlacetoPay::class);

        $mockPlacetoPay->shouldReceive('query')
            ->andReturn(new RedirectInformation($this->getQuery('rejected')));

        $mockGateway = Mockery::mock(PlacetopayGateway::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mockGateway->shouldAllowMockingProtectedMethods();
        $mockGateway->shouldReceive('getPaymentPlacetoPay')
            ->andReturn($mockPlacetoPay);

        $this->app->instance(PlacetopayGateway::class, $mockGateway);

        $job = new SolutionPaymentJob($payment);
        $job->handle();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'REJECTED',
        ]);
    }

    protected function getCreateSession($type)
    {
        $responses = json_decode(file_get_contents(__DIR__.'/../../Fixtures/createSession_responses.json'), true);

        return $responses[$type] ?? null;
    }

    protected function getQuery($type)
    {
        $responses = json_decode(file_get_contents(__DIR__.'/../../Fixtures/query_responses.json'), true);
        return $responses[$type] ?? null;
    }
}
