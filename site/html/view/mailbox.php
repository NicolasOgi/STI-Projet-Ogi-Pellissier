<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mailbox</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="css/mailbox.css" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="grid email">
                <div class="grid-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h2 class="grid-title"><i class="fa fa-inbox"></i>Mailbox</h2>
                            <a href="message.php" class="btn btn-block btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i>&nbsp;&nbsp;NEW MESSAGE</a>
                            <hr>
                            <a href="usermodify.php" class="btn btn-block btn-success" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i>&nbsp;&nbsp;Change password</a>
                            <hr>
                            <a href="users.php" class="btn btn-block btn-warning" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Users list</a>
                        </div>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="date">10.10.21</td>
                                            <td class="sender">Larry Gardner</td>
                                            <td class="subject">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed</td>
                                            <td><button onclick="location.href = 'message.php'" type="button" class="btn btn-primary">Reply</button></td>
                                            <td><button onclick="location.href = 'details.php'" type="button" class="btn btn-info">Open</button></td>
                                            <td><button type="button" class="btn btn-danger">Delete</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>