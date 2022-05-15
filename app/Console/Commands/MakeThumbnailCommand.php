<?php

namespace App\Console\Commands;

use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class MakeThumbnailCommand extends Command
{
    /**
     * @var ThumbnailService
     */
    private $thumbnailService;

    /**
     * @var string
     */
    protected $signature = 'make:thumbnail {imagePath} {storagePath?}';

    /**
     * @var string
     */
    protected $description = 'Create image thimbnail and save to google cloud';

    /**
     * @param ThumbnailService $thumbnailService
     *
     * @return void
     */
    public function __construct(ThumbnailService $thumbnailService)
    {
        parent::__construct();

        $this->thumbnailService = $thumbnailService;
    }

    /**
     * @return string
     */
    public function handle()
    {
        $imagePath = $this->argument('imagePath');
        
        $storagePath = $this->argument('storagePath') ?? 'gcs';

        return $this->thumbnailService->generateThumbnail($imagePath, $storagePath);
    }
}
