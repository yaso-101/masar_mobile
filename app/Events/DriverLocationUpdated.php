<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Broadcast immediately instead of queueing: a GPS ping is useless once it's stale,
// and this way only Reverb needs to be running (no queue worker).
class DriverLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rideIds;
    public $lat;
    public $lng;

    /**
     * Create a new event instance.
     */
    public function __construct(array $rideIds, $lat, $lng)
    {
        $this->rideIds = $rideIds;
        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // One private channel per ride in the car, e.g. 'private-ride-tracking.5'.
        // Only that ride's student and driver may listen (see routes/channels.php).
        return array_map(
            fn ($rideId) => new PrivateChannel('ride-tracking.' . $rideId),
            $this->rideIds
        );
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'driver.moved';
    }

    /**
     * Only send the coordinates (not the other students' ride IDs).
     */
    public function broadcastWith(): array
    {
        return ['lat' => $this->lat, 'lng' => $this->lng];
    }
}
