<div class=top><?php $this->partial("shared/ad_navbar"); ?></div>
{{ content() }}

<div class="jumbotron">
    <h1>Page not found</h1>
    <p>Sorry, you have accessed a page that does not exist or was moved</p>
    <p>{{ link_to('index', 'Home', 'class': 'btn btn-primary') }}</p>
</div>