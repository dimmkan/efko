<html>
<head>
    <link href="../includes/styles/styleListLA/la_style.css" rel="stylesheet" type="text/css">
</head>
<body>


<table border="0" cellpadding="0" cellspacing="0" class="tbl1" width="800">
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr valign="top">
        <td width="60%" height="91"><img src="../includes/styles/styleListLA/single_pixel.gif" width="1" height="140"></td>
        <td width="40%" height="91" colspan="3" valign="top" align="center">

        </td>
    </tr>
    <tr>
        <td colspan="3"  >
    <tr>
        <td valign="top" colspan="4">
            <table width="200%" border="0" cellspacing="25">
                <tr>
                    <td width="8%" valign="top"><img src="../includes/styles/styleListLA/single_pixel.gif" width="140" height="1"><br>
                        <p>Меню<br>
                            <a  class="menu" href="../index.php?action=listLA">К списку</a><br>
                            <?php if(!$_SESSION['user']->rules) {?>
                            <a  class="menu" href="../index.php?action=newLeaveApp">Новая заявка</a><br>
                            <?php }?>
                            <a  class="menu" href="../index.php?action=logout">Выйти</a> </p>
                    </td>
                    <td align="center" width="92%">
                        <table class="table">
                            <?php if ( isset( $results['errorMessage'] ) ) { ?>
                                <tr class="tr"> <div class="errorMessage"><?php echo $results['errorMessage'] ?></div> </tr>
                            <?php } ?>
                            <?php if ( isset( $results['statusMessage'] ) ) { ?>
                                <tr class="tr"> <div class="statusMessage"><?php echo $results['statusMessage'] ?></div> </tr>
                            <?php } ?>
                            <tr class="tr">
                                <th class="th">Номер заявки</th>
                                <th class="th">ФИО Сотрудника</th>
                                <th class="th">Начало отпуска</th>
                                <th class="th">Конец отпуска</th>
                                <th class="th">Описание</th>
                            </tr>
                            <?php foreach ( $results['leaveApps'] as $leaveApp ) { ?>
                            <tr class="tr" <?php if($leaveApp->userid == (int)$_SESSION['user']->id || $_SESSION['user']->rules == "1"){
                                echo "onclick=\"location='index.php?action=editLeaveApp&amp;leaveAppId={$leaveApp->id}'\"";
                            }?>>
                                <td class="td"><?php echo ($leaveApp->id);?></td>
                                <td class="td"><?php echo ($leaveApp->userdescr);?></td>
                                <td class="td"><?php echo date('j M Y', $leaveApp->datebeg);?></td>
                                <td class="td"><?php echo date('j M Y', $leaveApp->dateend);?></td>
                                <td class="td"><?php echo ($leaveApp->descr);?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
            </table>
            <h2>&nbsp;</h2>
        </td>
    </tr>
</table>
<!-- Do not remove this div -->
<div align="center"><p>
        <a href="http://www.logodesignweb.com/">Logo design web</a>
        | <a href="http://www.logodesignweb.com/webhostingguide/">Web hosting guide</a>
        | <a href="http://www.logodesignweb.com/stockphoto/">Public domain stock photos</a>
    </p>
    <br>
</div>
<!-- End of footer div -->

<div style="font-size: 0.8em; text-align: center; margin-top: 1.0em; margin-bottom: 1.0em;">
    <a href="http://web-mastery.info/">Web-Mastery INFO</a>
</div>
</body>
</html>