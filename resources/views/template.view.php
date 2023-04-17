<?php require('partials/header.php'); ?>

<h1>Create a template</h1>

<form method="post" action="/template/save" enctype="application/x-www-form-urlencoded">

    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="csrf-token" value="<?=$csrfToken?>">
    <div class="input-item row">
        <label for="input-template-name">Name</label>
        <input id="input-template-name" type="text" name="name" placeholder="Name of template" value="<?=$name?>" autofocus>
    </div>
    <div class="input-item row">
        <label for="input-template-body">Body</label>
        <textarea id="input-template-body" name="body"><?=$body?></textarea>
    </div>
    <p class="subtext">
        You can define dynamic variables by enclosing them within {{double brackets}}.
        Variables can contain a maximum of 2 words.
    </p>
    <div id="buttons">
        <button type="submit">Save</button>
        <?php if ($body): ?><button type="button" id="delete">Delete</button><?php endif; ?>
    </div>
</form>

<script>
    const body = document.getElementById('input-template-body');
    body.placeholder = "Hello {{name}},\r\n\r\nWill you be available at {{time}} for an online meeting?";

    <?php if ($body): ?>
    document.getElementById('delete').addEventListener('click', (e) => {
        const templateId = '<?=$id?>';
        e.target.disabled = true;
        const sure = confirm('Are you sure you want to delete this template?');
        if (sure) {
            fetch(`/template/delete/${templateId}`, {
                method: 'DELETE',
                cache: 'no-cache',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-csrf-token': '<?=$csrfToken?>',
                },
            }).then((r) => {
                r.json().then((response) => {
                    console.log(response.message);
                    window.location = '/';
                })
            }).catch((r) => {
                r.json().then((response) => {
                    console.error(response.message);
                });
            }).finally(() => {
                e.target.disabled = false;
            });
        } else {
            e.target.disabled = false;
        }
    });
    <?php endif; ?>
</script>

<?php require('partials/footer.php'); ?>
