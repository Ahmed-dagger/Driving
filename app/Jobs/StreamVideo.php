<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg; // Make sure to use the correct FFMpeg package
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StreamVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $video;
    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lowBitrate = (new X264('aac'))->setKiloBitrate(250);
        $midBitrate = (new X264('aac'))->setKiloBitrate(500);
        $highBitrate = (new X264('aac'))->setKiloBitrate(1000);

        FFMpeg::fromDisk('local')
            ->open($this->video->path)
            ->exportForHLS()
            ->onProgress(function($percent) {
                $this->video->update([
                    'percent' => $percent,
                ]);
            })
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save('public/videos/' . $this->video->id . '/' . $this->video->id . '.m3u8');
        }
}
