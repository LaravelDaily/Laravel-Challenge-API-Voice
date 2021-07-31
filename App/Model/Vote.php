public function scopeFindQuestion($builder,int $question_id){
    return $builder->where([
            ['user_id','=',auth()->id()],
            ['question_id','=',$question_id
    ]);
}