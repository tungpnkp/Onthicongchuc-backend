<?php


namespace App\Services;

use App\Entities\Question;
use App\Repositories\ExamRepositoryEloquent;
use DB;
use Illuminate\Support\Str;

class ExamService implements AppService
{
    protected $repository;

    public function __construct(ExamRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function pluck($column, $key = null)
    {
        return $this->repository->pluck($column, $key);
    }

    public function sync($id, $relation, $attributes, $detaching = true)
    {
        return $this->repository->sync($id, $relation, $attributes, $detaching);
    }

    public function syncWithoutDetaching($id, $relation, $attributes)
    {
        return $this->repository->syncWithoutDetaching($id, $relation, $attributes);
    }

    public function all($columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function paginate($limit = null, $columns = ['*'])
    {
        return $this->repository->paginate($limit, $columns);
    }

    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->repository->simplePaginate($limit, $columns);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    public function findByField($field, $value, $columns = ['*'])
    {
        return $this->repository->findByField($field, $value, $columns);
    }

    public function findWhere(array $where, $columns = ['*'])
    {
        return $this->repository->findWhere($where, $columns);
    }

    public function create(array $attributes)
    {
        return $this->repository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->repository->update($attributes, $id);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->repository->updateOrCreate($attributes, $values);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function orderBy($column, $direction = 'asc')
    {
        return $this->repository->orderBy($column, $direction);
    }

    public function with($relations)
    {
        return $this->repository->with($relations);
    }

    public function firstOrNew(array $attributes = [])
    {
        return $this->repository->firstOrNew($attributes);
    }

    public function firstOrCreate(array $attributes = [])
    {
        return $this->repository->firstOrCreate($attributes);
    }

    public function whereHas($relation, $closure)
    {
        return $this->repository->whereHas($relation, $closure);
    }

    public function getDetail($id)
    {
        $exam = $this->findByField('id', $id)->first();
        if (is_object($exam)) {
            return $exam;
        }
        return (object)[];
    }

    public function resultExam($exam)
    {
        $questions["questions_1"] = [];
        if ($exam->type == 1) {
            $questions_1 = $exam->questions()->where("type", 1)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_1));
            $questions_2 = $exam->questions()->where("type", 2)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_2));
            $questions_3 = $exam->questions()->where("type", 3)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_3));
            $questions_4 = $exam->questions()->where("type", 4)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_4));
        } else if ($exam->type == 2) {
            $questions_1 = $exam->questions()->where("type", 1)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_1));
//            $questions_2 = $exam->questions()->where("type", 2)->get()->groupBy('content_number')->random(1)->first();
//            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_2));
//            $questions_3 = $exam->questions()->where("type", 3)->get()->groupBy('content_number')->random(1)->first();
//            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_3));
//



            $questions_2 = $exam->questions()->where("type", 2)->inRandomOrder()->limit(5)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_2));
            $questions_3 = $exam->questions()->where("type", 3)->inRandomOrder()->limit(10)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_3));
        } else if ($exam->type == 3) {
            $questions_1 = $exam->questions()->where("type", 1)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_1));
            $questions_2 = $exam->questions()->where("type", 2)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_2));
            $questions_3 = $exam->questions()->where("type", 3)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_3));
            $questions_4 = $exam->questions()->where("type", 4)->inRandomOrder()->limit(15)->get();
            $questions["questions_1"] = array_merge($questions["questions_1"], $this->format_question($questions_4));
        } else {
            $questions = [];
        }
        return (object)$questions;
    }

    public function format_question($questions): array
    {
        $data = [];
        if (!empty($questions)) {
            foreach ($questions as $k => $question) {
                $data[$k]['id'] = $question->id;
                $data[$k]['exam_id'] = $question->exam_id;
                $data[$k]['title'] = $question->title;
                $data[$k]['content'] = $question->content;
                $data[$k]['answer'] = $question->{Str::lower($question->answer)};
                $incorrect = [$question->a, $question->b, $question->c, $question->d];
                $incorrect = array_diff($incorrect, [$data[$k]['answer']]);
                $incorrect = array_values($incorrect);
                $data[$k]['incorrect_answer'] = $incorrect;
                $data[$k]['type'] = $question->type;
                $data[$k]['status'] = $question->status;
                $data[$k]['note'] = $question->note;
                $data[$k]['created_at'] = $question->created_at->format("d/m/Y");
                $data[$k]['updated_at'] = $question->updated_at->format("d/m/Y");
            }
            return $data;
        }
        return $data;

    }
}
