<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $course->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $course->name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $course->description !!}</p>
</div>

<!-- Banner Field -->
<div class="form-group">
    {!! Form::label('banner', 'Banner:') !!}
    <p>{!! $course->banner !!}</p>
</div>

<!-- Thumbnail Field -->
<div class="form-group">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    <p>{!! $course->thumbnail !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $course->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $course->updated_at !!}</p>
</div>

