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

            @php
                $ogImageMedia = $item->getFirstMedia('og_image');
            @endphp

            {!! Form::crop('og_image', $ogImageMedia, [
                'label' => [
                    'title' => 'og:image',
                ],
                'image' => [
                    'filepath' => isset($ogImageMedia) ? url($ogImageMedia->getUrl()) : '',
                    'filename' => isset($ogImageMedia) ? $ogImageMedia->file_name : '',
                ],
                'crops' => [
                    [
                        'title' => 'Выбрать область',
                        'name' => 'default',
                        'ratio' => '968/475',
                        'value' => isset($ogImageMedia) ? $ogImageMedia->getCustomProperty('crop.default') : '',
                        'size' => [
                            'width' => 968,
                            'height' => 475,
                            'type' => 'min',
                            'description' => 'Минимальный размер области — 968x475 пикселей'
                        ],
                    ],
                ],
            ]) !!}

        </div>
    </div>
</div>
