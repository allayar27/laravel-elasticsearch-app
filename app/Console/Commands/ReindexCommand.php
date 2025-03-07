<?php

namespace App\Console\Commands;

use App\Models\Article;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Indexing all articles. This might take a while...');
        foreach (Article::cursor() as $article)
        {
            $this->elasticsearch->index([
                'index' => $article->getSearchIndex(),
                'type' => $article->getSearchType(),
                'id' => $article->getKey(),
                'body' => $article->toSearchArray(),
            ]);

            $this->output->write('.');
        }
        $this->output->writeln('');
        $this->info('Done!');
    }
}
