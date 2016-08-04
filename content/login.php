<script type="text/javascript">
    $(function() {
        // Jquery UI på skjema knappene
        $("input:submit, input:button").button();
        $('#agreement').buttonset();
        //$("label").inFieldLabels();
    });
</script>

<?php if (($validationresults == FALSE && isset($_POST['submit']))) echo '
        <div id="FormFeedback" class="ui-state-highlight ui-corner-all" style="float:none;margin-left:auto;margin-right:auto;">
            <span class="ui-icon ui-icon-info" style="float:left; margin-right: .3em;"></span>
            Please enter a valid username and/or password!
        </div><br />
'; ?>
<div id="LoginWrapper">
    <table id="FormatTable">
        <tr>
            <td>&nbsp;&nbsp;<h1>Sign in</h1></td>
        </tr>
        <tr>
            <td>
                <form action="?" id="formID" method="post">
                    <fieldset>
                        <table>
                            <tr>
                                <td>
                                    <p>
                                        <label class="outField" for="user" style="">Username:</label>
                                        <input type="text" class="<?php if ($validationresults == FALSE) echo "invalid"; ?>" id="user" name="user" />
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <label class="outField" for="pass" style="">Password:</label>
                                        <input name="pass" type="password" class="<?php if ($validationresults == FALSE) echo "invalid"; ?>" id="pass" />
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <input type="submit" value="Login" name="submit" />
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <a href="?content=register">Create new account?</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </td>
        </tr>
    </table>
</div>