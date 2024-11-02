<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'success'): ?>
        <div class="alert alert-success">Contact request sent successfully!</div>
    <?php elseif ($_GET['status'] == 'error'): ?>
        <div class="alert alert-danger">Failed to send contact request.</div>
    <?php endif; ?>
<?php endif; ?>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="requests">
                        <div class="title">
                            <h1>Add your Contacts</h1>
                            <button type="button" class="btn" data-dismiss="modal" aria-label="Close"><i
                                    class="material-icons">close</i></button>
                        </div>
                        <div class="content">
                            <form action="sendContactRequest.php" method="post">
                                <div class="form-group">
                                    <label for="recipientId">Select User:</label>
                                    <select class="form-control" id="recipientId" name="recipientId" required>
                                        <option value="">Select a recipient...</option>
                                        <?PHP $users = UserController::getAllUsers(); ?>
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?= htmlspecialchars($user['UserID']) ?>">
                                            <?= htmlspecialchars($user['UserName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn button w-100">Send Contact Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>