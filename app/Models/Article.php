<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Article extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'category_id',
        'title',
        'body',
        'tags'
    ];

    protected $casts = [
        'tags' => 'json',
    ];



//    public function toSearchableArray(): array
//    {
//        return [
//            'id' => $this->id,
//            'title' => $this->title,
//            'created_at' => $this->created_at
//        ];
//    }
//
//    protected function makeAllSearchableUsing(Builder $query): Builder
//    {
//        return $query->with('category');
//    }
//
//    public function mappableAs(): array
//    {
//        return [
//            'id' => 'keyword',
//            'title' => 'text',
//            'created_at' => 'date',
//            'category' => [
//                'name' => 'text',
//            ],
//        ];
//    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function toElasticsearchDocumentArray(): array
    {

    }
}
