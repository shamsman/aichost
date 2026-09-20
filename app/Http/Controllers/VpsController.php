<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\CloudComputeInterface;
use App\Models\Product;
use App\Models\VpsInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VpsController extends Controller
{
    public function __construct(
        protected CloudComputeInterface $cloudCompute
    ) {}

    public function index(): View
    {
        $vpsProduct = Product::where('type', Product::TYPE_VPS)->first();
        $vpsPlans = $vpsProduct 
            ? $vpsProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        return view('vps.index', compact('vpsPlans'));
    }

    public function action(Request $request, VpsInstance $instance, string $action): JsonResponse
    {
        abort_unless($instance->user_id === auth()->id(), 403, 'Unauthorized access to this VPS instance.');
        abort_unless(in_array($action, ['start', 'stop', 'restart', 'status'], true), 422, 'Invalid action specified.');

        try {
            $result = match ($action) {
                'start'   => $this->cloudCompute->startInstance($instance->instance_name, $instance->gcp_zone),
                'stop'    => $this->cloudCompute->stopInstance($instance->instance_name, $instance->gcp_zone),
                'restart' => $this->cloudCompute->restartInstance($instance->instance_name, $instance->gcp_zone),
                'status'  => $this->cloudCompute->getInstanceStatus($instance->instance_name, $instance->gcp_zone),
            };

            $newStatus = match ($action) {
                'start', 'restart' => 'running',
                'stop'             => 'stopped',
                default            => $instance->status,
            };

            $instance->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'action'  => $action,
                'status'  => $newStatus,
                'message' => $result->message,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Action failed: {$e->getMessage()}",
            ], 500);
        }
    }
}
