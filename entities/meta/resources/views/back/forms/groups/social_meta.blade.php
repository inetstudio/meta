@php
    $item = $value;
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">
            <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseSocialMeta" aria-expanded="false"
               class="collapsed">Социальные мета теги</a>
        </h5>
    </div>
    <div id="collapseSocialMeta" class="collapse" aria-expanded="false">
        <div class="panel-body">

            {!! Form::string('meta[og:title]', $item->getMeta('og:title'), [
                'label' => [
                    'title' => 'og:title',
                ],
            ]) !!}

            {!! Form::string('meta[og:description]', $item->getMeta('og:description'), [
                'label' => [
                    'title' => 'og:description',
                ],
            ]) !!}

            <x-inetstudio.uploads-package.uploads::fields.back.media
                field-name="og_image"
                label="og:image"
                :item="$item"
            />

        </div>
    </div>
</div>
