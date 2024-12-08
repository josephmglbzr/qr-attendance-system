<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.tailwindcss.com"></script>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply,multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .student-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .student-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>
<body>
<nav class="w-full flex text-white text-sm lg:text-base items-center  p-2 justify-evenly bg-blue-700 gap-2">
        <div class="flex items-center gap-4">
            <img src="logo.png" class="w-16 md:w-24 lg:w-28"  alt="">
            <a class="" href="#">OCC Qr Attendance System</a>
        </div> 
     
        <div>
            <a class="hover:text-blue-200 transition-all" href="index.php">Back</a>
        </div>
       
          
    </nav>

    <div class="main">
        
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>List of Students</h4>
                    <button class="btn btn-dark text-white text-sm border-none bg-blue-800 p-2" data-toggle="modal" data-target="#addStudentModal">Add Student</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead class="text-sm bg-blue-950 text-gray-400 text-sm">
                            <tr>
                                <th class="text-sm" scope="col">#</th>
                                <th class="text-sm" scope="col">Name</th>
                                <th class="text-sm" scope="col">Course & Section</th>
                                <th class="text-sm" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm lg:text-xs">

                            <?php 
                                include ('./conn/conn.php');

                                $stmt = $conn->prepare("SELECT * FROM tbl_student");
                                $stmt->execute();
                
                                $result = $stmt->fetchAll();
                
                                foreach ($result as $row) {
                                    $studentID = $row["tbl_student_id"];
                                    $studentName = $row["student_name"];
                                    $studentCourse = $row["course_section"];
                                    $qrCode = $row["generated_code"];
                                ?>

                                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" id="studentID-<?= $studentID ?>"><?= $studentID ?></th>
                                    <td id="studentName-<?= $studentID ?>"><?= $studentName ?></td>
                                    <td id="studentCourse-<?= $studentID ?>"><?= $studentCourse ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= $studentID ?>"><img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="16"></button>

                                            <!-- QR Modal -->
                                            <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><?= $studentName ?>'s QR Code</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrCode ?>" alt="" width="300">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="btn btn-secondary btn-sm" onclick="updateStudent(<?= $studentID ?>)">&#128393;</button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?= $studentID ?>)">&#10006;</button>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudent">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-sm">
                    <form action="./endpoint/add-student.php" method="POST">
                        <div class="form-group">
                            <label for="studentName">Full Name:</label>
                            <input type="text" class="form-control" id="studentName" name="student_name">
                        </div>
                        <div class="form-group">
                            <label for="studentCourse">Course and Section:</label>
                            <input type="text" class="form-control" id="studentCourse" name="course_section">
                        </div>
                        <button type="button" class="btn  form-control qr-generator bg-blue-800 text-white text-sm" onclick="generateQrCode()">Generate QR Code</button>

                        <div class="qr-con text-center flex flex-col gap-2 items-center" style="display: none;">
                            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                            <p>Take a pic with your qr code.</p>
                            <img class="mb-4" src="" id="qrImg" alt="">
                        </div>
                        <div class="modal-footer modal-close" style="display: none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="updateStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStudent">Update Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/update-student.php" class="text-sm" method="POST">
                        <input type="hidden" class="form-control" id="updateStudentId" name="tbl_student_id">
                        <div class="form-group">
                            <label for="updateStudentName">Full Name:</label>
                            <input type="text" class="form-control" id="updateStudentName" name="student_name">
                        </div>
                        <div class="form-group">
                            <label for="updateStudentCourse">Course and Section:</label>
                            <input type="text" class="form-control" id="updateStudentCourse" name="course_section">
                        </div>
                        <div class="modal-footer  text-sm">
                            <button type="button" class="btn btn-secondary text-white" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn bg-blue-800 text-white">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready( function () {
            $('#studentTable').DataTable();
        });

        function updateStudent(id) {
            $("#updateStudentModal").modal("show");

            let updateStudentId = $("#studentID-" + id).text();
            let updateStudentName = $("#studentName-" + id).text();
            let updateStudentCourse = $("#studentCourse-" + id).text();

            $("#updateStudentId").val(updateStudentId);
            $("#updateStudentName").val(updateStudentName);
            $("#updateStudentCourse").val(updateStudentCourse);
        }

        function deleteStudent(id) {
            if (confirm("Do you want to delete this student?")) {
                window.location = "./endpoint/delete-student.php?student=" + id;
            }
        }

        function generateRandomCode(length) {
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            let randomString = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }

            return randomString;
        }

        function generateQrCode() {
            const qrImg = document.getElementById('qrImg');

            let text = generateRandomCode(10);
            $("#generatedCode").val(text);

            if (text === "") {
                alert("Please enter text to generate a QR code.");
                return;
            } else {
                const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;

                qrImg.src = apiUrl;
                document.getElementById('studentName').style.pointerEvents = 'none';
                document.getElementById('studentCourse').style.pointerEvents = 'none';
                document.querySelector('.modal-close').style.display = '';
                document.querySelector('.qr-con').style.display = '';
                document.querySelector('.qr-generator').style.display = 'none';
            }
        }
    </script>
    
</body>
</html>