<html>
<head>
    <link href="../includes/styles/styleEditLA/editLAStyle.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="index.php?action=<?php echo $results['formAction'] ?>" method="post">
    <input type="hidden" name="leaveAppId" value="<?php echo $results['leaveApp']->id ?>">
    <input type="hidden" name="userid" value="<?php echo ($results['leaveApp']->userid ? $results['leaveApp']->userid : $_SESSION['user']->id); ?>">

    <?php if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>
    <ul>

        <li>
            <label for="description">Описание</label>
            <input type="text" name="descr" id="descr" placeholder="Описание" required autofocus
                   maxlength="250" value="<?php echo htmlspecialchars($results['leaveApp']->descr) ?>" <?php echo ($_SESSION['user']->rules || $results['leaveApp']->fixed ? " readonly" : "") ?>/>
        </li>
        <li>
            <label for="datebeg">Дата начала отпуска</label>
            <input type="date" name="datebeg" id="datebeg" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['leaveApp']->datebeg ? date( "Y-m-d", $results['leaveApp']->datebeg ) : "" ?>" <?php echo ($_SESSION['user']->rules || $results['leaveApp']->fixed ? " readonly" : "") ?>/>
        </li>
        <li>
            <label for="dateend">Дата окончания отпуска</label>
            <input type="date" name="dateend" id="dateend" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['leaveApp']->dateend ? date( "Y-m-d", $results['leaveApp']->dateend ) : "" ?>" <?php echo ($_SESSION['user']->rules || $results['leaveApp']->fixed ? " readonly" : "") ?>/>
        </li>

        <li>
            <?php if($_SESSION['user']->rules) {?> <label for="fixed">Зафиксирована</label> <?php }?>
            <input type="<?php echo(!$_SESSION['user']->rules) ? "hidden" : "checkbox"; ?>" name="fixed" <?php echo ($results['leaveApp']->fixed ? " checked" : "") ?> />
        </li>

    </ul>

    <div class="buttons">
        <input type="submit" name="saveChanges" value="Сохранить"/>
        <input type="submit" formnovalidate name="cancel" value="Отмена"/>
    </div>

</form>

<?php if(!$results['leaveApp']->fixed && !$_SESSION['user']->rules) { ?>
    <p><a href="index.php?action=deleteLeaveApp&amp;leaveAppId=<?php echo $results['leaveApp']->id ?>"
          onclick="return confirm('Удалить заявку?')">
            Удалить заявку
        </a>
    </p>
<?php } ?>
</body>
</html>