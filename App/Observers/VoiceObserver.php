public function creating(Voice $voice){
    $voice['user_id'] = auth()->id();
}