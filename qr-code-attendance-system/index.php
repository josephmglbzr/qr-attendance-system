<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

      

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
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
            <a class="hover:text-blue-200 transition-all" href="./masterlist.php">List of Students</a>
        </div>
       
          
    </nav>

    <div class="main">
         
        <div class="flex items-center flex-col sm:flex-row text-black gap-5 shadow-[rgba(0,_0,_0,_0.4)_0px_30px_90px] p-20">
            <div class="">
                <div class="scanner-con flex flex-col items-center">
                    <h5 class="text-sm lg:text-base">Scan your QR Code here for your attedance</h5>
                    <video id="interactive" class="w-64 lg:w-80 mt-5" width="100%">
                </div>

                <div class="qr-detected-container" style="display: none;">
                    <form action="./endpoint/add-attendance.php" method="POST">
                        <h4 class="text-center">Student QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                        <button type="submit" class="bg-blue-950 text-sm lg:text-base p-2 text-white mt-4 rounded-lg hover:blue-blue-700 transition-all">Submit Attendance</button>
                    </form>
                </div>
            </div>

            <div class="attendance-list">
                <h4 class="text-sm lg:text-base">List of Present Students</h4>
                    <table class="w-full text-xs lg:text-sm text-center text-gray-500 dark:text-gray-400 mt-5" id="attendanceTable">
                        <thead class="text-xs lg:text-sm text-gray-300 uppercase bg-blue-950">
                            <tr>
                                <th class="px-6 py-3">#</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Course & Section</th>
                                <th class="px-6 py-3">Time In</th>
                                <th class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
                                include ('./conn/conn.php');

                                $stmt = $conn->prepare("SELECT * FROM tbl_attendance LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id");
                                $stmt->execute();
                
                                $result = $stmt->fetchAll();
                
                                foreach ($result as $row) {
                                    $attendanceID = $row["tbl_attendance_id"];
                                    $studentName = $row["student_name"];
                                    $studentCourse = $row["course_section"];
                                    $timeIn = $row["time_in"];
                                ?>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="p-5"><?= $attendanceID ?></th>
                                    <td><?= $studentName ?></td>
                                    <td><?= $studentCourse ?></td>
                                    <td><?= $timeIn ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-danger delete-button" onclick="deleteAttendance(<?= $attendanceID ?>)">
                                                <img src="bin.png" class="w-7" alt="">
                                            </button>
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
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- instascan Js -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>

        
        let scanner;

        function startScanner() {
            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
                $("#detected-qr-code").val(content);
                console.log(content);
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = '';
                document.querySelector(".scanner-con").style.display = 'none';
            });

            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        document.addEventListener('DOMContentLoaded', startScanner);
        
        function deleteAttendance(id) {
            if (confirm("Do you want to remove this attendance?")) {
                window.location = "./endpoint/delete-attendance.php?attendance=" + id;
            }
        }
    </script>
</body>
</html>