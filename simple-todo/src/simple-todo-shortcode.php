<div class="card" style="width: 18rem;">
    <img class="card-img-top" src="..." alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title"><?php isset($item->title) ? esc_html_e($item->title) : '';?></h5>
        <p class="card-text">
            <?php isset($item->description) ? esc_html_e($item->description) : '';?>
        </p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
    </div>
</div>