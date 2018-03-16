<html>
<head>
    <script src="assets/js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="Resource/form_buttonDesign.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-theme.css">
    <style>
        .groupNameEntry {
            width: 50%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid black;
        }
    </style>
</head>
<body>
<?php

include_once "DbFile/dbconfig.php";
include_once "sessionStartCheck.php";
if (isset($_SESSION['email']) && $_SESSION['userStatus'] == 1) {

    $groupDetails = array();
    $sql = "select id,userGroupStatus,userStatus from userinfo WHERE id='" . $_SESSION['managerID'] . "'";

    $res = mysqli_query($conn, $sql);
    $ures = mysqli_fetch_array($res, MYSQLI_ASSOC);
    //$_SESSION['managerID']=$ures['id'];
    echo $ures['id'];
    if ($ures['userGroupStatus'] == 0) {
        echo "Haven't create a group!";
        echo "";
        include "groupCreation.html";

    }
    if ($ures['userGroupStatus'] == 1 && $ures['userStatus'] == 1) {
        $sqlCheck = "SELECT * from groupdetails WHERE manager_id='" . $_SESSION['managerID'] . "'";//Group name query
        $result = mysqli_query($conn, $sqlCheck);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo "<table class='table table-bordered'><thead><tr><th>Member Name</th><th>Action</th></tr></thead><tbody>";
        if (isset($row['group_id'])) {
            echo "<center><p>Group Name</p><h4>" . $row['group_name'] . "<a href='editGroupName.php' class='btn btn-link'>Edit</a></h4>";
            $gid = $row['group_id'];
            $_SESSION['groupID'] = $row['group_id'];
            echo "Group ID: " . $gid . "<br>";
            echo "<h4>Group Member List :</h4>";
            $sql2 = mysqli_query($conn, "select id,firstName,lastName from userinfo WHERE group_id='$gid'");
            while ($result2 = mysqli_fetch_assoc($sql2)) {
                $id=$result2['id'];
                echo "<tr><td>" . ucwords($result2['firstName'] . " " . $result2['lastName'])."</td><td><button class='btn btn-danger buttonDelete' id='$id'>Remove</button></td></tr>";
                echo "<br></b>";
            }
            echo "</tbody></table>";
            echo "</b></center>";
            ?>
            <div class="container">
                <div class="moreMemberAdd">
                    <button class="btn btn-info btn-lg" id="memberAddButton">Add More People</button>
                    <div class="groupMemberAddDiv" id="groupMemberAddDiv">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-2"><h4>Enter Email</h4></label>
                                <div class="col-sm-8">
                                    <input class="form-control input input-lg" id="memberAddQuery"
                                           placeholder="Enter Email Here To Find User"/>
                                    <ul id="groupAddQueryResult"></ul>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-warning btn-lg" id="hideAddDIv">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        }

    }
} elseif ((isset($_GET['groupShowId']))) {
    include_once "DbFile/dbconfig.php";
    $groupShowId = $_GET['groupShowId'];

    ?>
    <h3 id="showResult"></h3>
    <?php

    $query = "select firstName,lastName,group_name,manager_id,groupOtherDetails.house_address from userinfo,groupDetails,groupOtherDetails where userinfo.group_id='" . $groupShowId . "' 
    and groupDetails.group_id=userinfo.group_id and groupOtherDetails.group_id=userinfo.group_id";
    $result = mysqli_query($conn, $query);
    $house_address = "";
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['firstName'] . " " . $row['lastName'] . "<br>";
        $house_address = $row['house_address'];
    }
    echo $house_address;
    ?>
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="groupJoinForm">
        <input type="hidden" class="hidden" id="groupShowId" value="<?= $groupShowId ?>" name="groupShowId">
        <input type="button" value="Join" id="groupJoin" class="btn btn-primary">

    </form>
    <?php

} else {
    header("Location:signin.php");
}
?>
<script type="text/javascript">

    document.getElementById('groupMemberAddDiv').style.display = 'none';

    $(document).ready(function () { //jQuery Add
        $("#memberAddButton").click(function () {
            $("#memberAddButton").slideUp();
            $("#groupMemberAddDiv").slideDown();
        });
        $("#hideAddDIv").click(function () {
            $("#memberAddButton").slideDown();
            $("#groupMemberAddDiv").slideUp();
        });
        var search = $("#memberAddQuery");
        search.keyup(function () {
            var searchTerm = search.val();
            if (searchTerm !== "") {
                searchUser(searchTerm);
            }
            else {
                $('ul#groupAddQueryResult').empty();
            }
        });

        function searchUser(searchTerm) {
            $.ajax({
                type: "POST",
                url: 'searchUser.php',
                data: {'searchTerm': searchTerm},
                success: function (data) {
                    var d = $.parseJSON(data);

                    $('ul#groupAddQueryResult').empty();

                    for (i = 0; i < d.length; i++) {
                        $('ul#groupAddQueryResult').append('<li><a href="userDetails.php?userShowId=' + d[i].id + '">' + d[i].firstName + " " + d[i].lastName + '</a></li>');
                    }
                },
                error: function (result, xhr, status) {
                    console.log(xhr);
                }
            });
        }

        $("#groupJoin").click(function () {
            var groupShowId = $("#groupShowId").val();
            $.ajax({
                type: "POST",
                url: 'groupJoinDetails.php',
                data: {'groupShowId': groupShowId},
                success: function (data) {
                    $("#showResult").html(data);
                }

            });
        });
        $(".buttonDelete").on('click',function(){
            var id=this.id;
            deleteRecord(id);
        });

        function deleteRecord(id) {
            if(confirm("Are you sure you want to delete this row?")) {
                $.ajax({
                    url: "deleteGroupMember.php",
                    type: "POST",
                    data:'id='+id,
                    success: function(data){
                        $(this).remove();
                        $('#deleteReply').html(data);
                        location.reload();
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                });
            }
        }
    });
</script>
</body>
</html>
