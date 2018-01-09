@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="titles-container col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-pills">
                        <li role="presentation" class="active"><a href="#articles" role="tab" data-toggle="tab">Articles</a></li>
                        <li role="presentation"><a href="#tweets" role="tab" data-toggle="tab">Tweets</a></li>
                    </ul>
                </div>

                <div class="tab-content scrollable">
                    <div class="tab-pane active in fade" role="tabpanel" id="articles">
                        <ul class="list-group">
                            @foreach ($articles as $article)
                                <article-entry class="list-group-item" :article-id="'{{ $article['_id'] }}'">{{ $article['headline'] }}</article-entry>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" role="tabpanel" id="tweets">
                        <ul class="list-group">
                            @foreach ($tweets as $tweet)
                                <tweet-entry class="list-group-item" :tweet-id="'{{ $tweet['id_str'] }}'" :title="'{{ $tweet['text'] }}'"></tweet-entry>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-container col-md-6">
            <display id="display" class="scrollable" />
        </div>
    </div>
</div>
@endsection

@push('customJS')
<script>
    jQuery(function($) {
        $(".tab-pane.active .list-group li:first").click();

        var calcMaxHeight = function() {
            var height = $(window).innerHeight() - 30;
            $(".tab-content").css('max-height', height - $(".tab-content").offset().top);
            $("#display").css('max-height', height - $("#display").offset().top);
        };

        $(window).on('resize', calcMaxHeight);
        calcMaxHeight();
    })
</script>
@endpush
