<ul id="settingsTab" class="nav nav-tabs bordered">
    <?php foreach($_tabs as $_tab): ?>
        <li class="<?php echo $_tab['name'] == $_current ? 'active' : '' ?>">
            <a style="cursor: pointer;" href="<?php echo $_tab['url'] ?>"><i class="<?php echo $_tab['icon'] ?>"></i> <?php echo $_tab['label'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>