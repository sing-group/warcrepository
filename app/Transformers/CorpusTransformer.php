<?

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Corpus;


class CorpusTransformer extends TransformerAbstract
{
    public function transform(Corpus $corpus)
    {
        return [
            'name' => $corpus->name,
            'path' => $corpus->path,
            'added' => date('Y-m-d', strtotime($corpus->created_at))
        ];
    }
}