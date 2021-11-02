<?php require('partials/header.php'); ?>

    <h1 id="template-name">Templates</h1>

<form id="canvas">
    <div id="inputs"></div>
    <hr>
    <output id="output"></output>
    <?php if ($hasTemplates): ?>
    <div id="buttons">
        <button type="submit" id="copy">Copy</button>
        <button type="button" id="clear">Clear form</button>
    </div>
    <?php else: ?>
        <p class="subtext">No templates are available, why don't you <a href="/template/create" title="Create a template">create one</a>?</p>
    <?php endif; ?>
</form>

<script>
    const data = <?=$json?>;
    const headerEl = document.getElementById('template-name');
    const outputEl = document.getElementById('output');
    const inputsEl = document.getElementById('inputs');
    let template = data.length ? data[0] : null;
    let inputs = [];

    const templateNav = document.getElementById('template-nav');

    data.forEach((i, k) => {
        if (k === 0 && !location.hash) {
            location.hash = `#templateId=${i.id}`;
        }
        const item = document.createElement('li');
        const anchor = document.createElement('a');
        anchor.href = `#templateId=${i.id}`;
        anchor.innerText = i.name;
        item.appendChild(anchor);
        templateNav.appendChild(item);
    });

    const checkHash = () => {
        const params = new URLSearchParams(location.hash.slice(1));
        const templateId = params.get('templateId');
        template = data.filter((t) => t.id === templateId)[0];
        handle();
    };

    window.addEventListener("hashchange", checkHash, false);

    const handleInputChange = (e) => {
        inputs = inputs.map((input) => {
            if (input.key === e.target.name) {
                input.value = e.target.value;
            }
            return input;
        });
        localStorage.setItem(template.id, JSON.stringify(inputs));
        renderBody();
    }

    const wait = async (callback, time) => {
        return new Promise(resolve => {
            setTimeout(() => {
                resolve(callback());
            }, time);
        });
    };

    const message = (msg, classes) => {
        const body = document.getElementById('messages');
        let modal = document.createElement('div');
        const hide = () => {
            wait(() => modal.classList.add('hide'), 4000)
                .then(() => {});
        }
        modal.classList.add('message');
        modal.innerText = msg;
        wait(() => body.appendChild(modal), 0)
            .then(() => hide())
    };

    const getCopyPermissionsAPI = async () => {
        if (location.protocol !== 'https:') {
            return false;
        }
        if (!navigator.permissions) {
            return !!navigator.clipboard;
        }
        const result = await navigator.permissions.query({ name: 'clipboard-write' });
        return result.state === 'granted';
    }

    const copy = async (text) => {
        const hasPermission = await getCopyPermissionsAPI();

        document.getElementById('canvas').checkValidity();

        if (hasPermission) {
            navigator.clipboard.writeText(text)
                .then(() => console.log(`Copied '${text}' to clipboard`))
                .catch(() => console.error(`Unable to copy '${text}' to clipboard`));
        } else {
            const tmpTextarea = document.createElement('textarea');
            tmpTextarea.id = 'copyTextDom';
            tmpTextarea.innerHTML = text;
            tmpTextarea.style.position = 'absolute';
            tmpTextarea.style.top = '0';
            tmpTextarea.style.left = '5000vw';
            tmpTextarea.style.opacity = '0.00001';

            document.body.appendChild(tmpTextarea);

            tmpTextarea.select();
            document.execCommand('copy');
            tmpTextarea.remove();
        }
    }

    <?php if ($hasTemplates): ?>

    const copyBtn = document.getElementById('canvas');
    copyBtn.addEventListener('submit', (e) => {
        e.preventDefault();
        copy(outputEl.innerText).then(() => message(`${template.name} template copied!`));
    });


    document.getElementById('clear')
        .addEventListener('click', (e) => {
            if (localStorage.getItem(template.id)) {
                localStorage.removeItem(template.id);
                handle();
            }
        });
    <?php endif; ?>

    const renderBody = () => {
        let tempBody = template.body;
        inputs.forEach((input) => {
            if (input.value) {
                tempBody = tempBody.replaceAll(input.key, input.value);
            }
        });
        outputEl.innerText = tempBody;
    }

    const initInputs = () => {
        if (!template) {
            return;
        }
        const matches = template.body.matchAll(/{{(\S+?\s{0,1}\S+)}}/gm);
        const tmp = [...matches].map((v, i) => {
            return JSON.stringify({
                key: v[0],
                label: v[1],
            });
        });
        inputs = tmp
            .filter((i, p) => tmp.indexOf(i) === p)
            .map((i, p) => {
                return {
                    ...JSON.parse(i),
                    index: p,
                    value: null,
                }
            });
    };

    const handle = () => {
        if (!template) {
            return;
        }
        headerEl.innerText = template.name;
        const editAnchorSpan = document.createElement('span');
        const editAnchor = document.createElement('a');
        editAnchor.href = `/template/edit/${template.id}`;
        editAnchor.title = `Edit ${template.name}`;
        editAnchor.innerText = 'Edit';
        editAnchorSpan.classList.add('sub-edit');
        editAnchorSpan.appendChild(editAnchor);
        headerEl.appendChild(editAnchorSpan);

        const cache = localStorage.getItem(template.id);
        if (cache) {
            inputs = JSON.parse(cache);
        } else {
            initInputs();
        }
        inputsEl.innerHTML = '';
        let focusEl = null;
        inputs.forEach((input, i) => {
            const inputId = `i${input.index}`;
            const inputContainerEl = document.createElement('div');
            inputContainerEl.classList.add('input-item');
            const inputEl = document.createElement('input');
            inputEl.id = inputId;
            inputEl.name = input.key;
            inputEl.type = 'text';
            inputEl.placeholder = input.label;
            inputEl.required = true;
            inputEl.value = input.value;
            if (i === 0) {
                focusEl = inputEl;
                inputEl.autofocus = true;
            }
            inputEl.addEventListener('change', handleInputChange);
            inputEl.addEventListener('input', handleInputChange);

            const labelEl = document.createElement('label');
            labelEl.htmlFor = inputId;
            labelEl.innerText = input.label;

            inputContainerEl.appendChild(labelEl);
            inputContainerEl.appendChild(inputEl);
            inputsEl.appendChild(inputContainerEl);
        });
        if (focusEl) {
            focusEl.focus();
        } else {
            inputsEl.innerHTML = '<span id="noInputs">No inputs for this template</span>';
        }

        outputEl.value = template.body;
        renderBody();
    };

    checkHash();
</script>

<?php require('partials/footer.php'); ?>
