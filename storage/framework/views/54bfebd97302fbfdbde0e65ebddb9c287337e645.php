<?php date_default_timezone_set("Asia/Calcutta");  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: solid 2px;
            padding: 10px 5px;
        }

        tr {
            text-align: center;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .page-footer {
            position: fixed;
            left: 0;
            bottom: 2em;
            width: 100%;
            text-align: center;
        }

        .month {
            text-align: center;
        }
    </style>
</head>
<body style="font-family: Calibri, sans-serif;">
<?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h2 style="text-align: center; top: 0;">
            Work Report for <span><?php echo e($employee['personalData']['name']); ?></span>
        </h2>
        <div class="container">
            <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("employee_details",$employee['sections']))): ?>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>
                                Employee ID
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['code']); ?>

                            </td>
                            <th>
                                Employee Status
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['status']); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Date Of Joining
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['joiningDate']); ?>

                            </td>
                            <th>
                                Date Of Relieving
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['relievingDate']); ?>

                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
            <?php endif; ?>
            <?php if(count($employee['personalData']['leavesData'])): ?>
                <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("leaves_summary
                ",$employee['sections']))): ?>
                    
                    <?php 
                        $approved = 0;
                        $unApproved = 0;
                    ?>
                    <?php $__currentLoopData = $employee['personalData']['leavesData']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($value->approval_status == "Y"): ?>
                            <?php $approved++; ?>
                        <?php else: ?>
                            <?php $unApproved++; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div style="background-color: lightgrey;">
                        <h3>Leaves Summary</h3>
                    </div>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        # No Of Leave Apply
                                    </th>
                                    <td>
                                        <?php echo e(count($employee['personalData']['leavesData'])); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        # No Of Leave Approved
                                    </th>
                                    <td>
                                        <?php echo e($approved); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        # No Of Leave Unapproved
                                    </th>
                                    <td>
                                        <?php echo e($unApproved); ?>

                                    </td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                
                    <div class="container">
                        <h3 class="month">Leaves Requests</h3>
                        <table id="example2" role="grid">
                            <thead>
                                <tr role="row">
                                    <th>S. No.</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Status</th>
                                    <th>No Of Days</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $employee['personalData']['leavesData']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+1); ?></td>
                                    <td><?php echo e(date('d M Y',strtotime($value->from_date))); ?></td>
                                    <td><?php echo e(date('d M Y',strtotime($value->to_date))); ?></td>
                                    <td><?php echo e(($value->approval_status == "Y") ? "Approved" : "UnApproved"); ?></td>
                                    <td><?php echo e($value->total_days); ?></td>
                                    <td><?php echo e($value->reason_for_leave); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("report_period",$employee['sections']))): ?>
            <div style="background-color: lightgrey;">
                <h3>
                    Report for the duration <?php echo e($startDate); ?> to <?php echo e($endDate); ?>

                </h3>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>
                                # of working days
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['totalWorkingDays']); ?>

                            </td>
                            <th>
                                Total hours to work
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['totalWorkingHours']); ?>

                            </td>
                        </tr>
                        <tr>
                            <?php
                                $validTime = "10:15:00";
                                $totalDays = 0;
                            ?>
                            
                            <?php $__currentLoopData = $employee['loginData']['taskData']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                //$totalDays = 0;
                            ?>
                            <?php $__currentLoopData = $data['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loginData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(date('H:i:s', strtotime($loginData['login'])) <=  $validTime): ?>
                                    <?php $totalDays++; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th>
                                # of days present <?php echo e(date("h:i")); ?>

                            </th>
                            <td>
                                <?php echo e($totalDays); ?>

                            </td>
                            <th>
                                Total hours logged
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['totalHoursWorked']); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                % of days present
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['totalWorkingDays'] ? number_format((($totalDays / ($employee['personalData']['totalWorkingDays'])) * 100), 2) : 0); ?>

                            </td>
                            <th>
                                % hours logged
                            </th>
                            <td>
                                <?php echo e($employee['personalData']['totalWorkingHours'] ? number_format((($employee['personalData']['totalHoursWorked'] / ($employee['personalData']['totalWorkingHours'])) * 100), 2) : 0); ?>

                            </td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <?php endif; ?>
            <div class="container">
                <?php $__currentLoopData = $employee['loginData']['taskData']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("summary_section",$employee['sections']))): ?>
                    <h3 class="month"><?php echo e($key); ?></h3>
                    <table id="example2" role="grid">
                        <thead>
                            <tr role="row">
                                <th>S. No.</th>
                                <th width="18%">Date</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Hours At Work</th>
                                <th>Daily Report Available</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            <?php
                                $sNo = 1;
                            ?>
                            <?php $__currentLoopData = $data['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loginData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(date('H:i:s', strtotime($loginData['login'])) <=  $validTime): ?>
                                    <tr>
                                        <td><?php echo e($sNo++); ?></td>
                                        
                                        <td><?php echo $loginData['loginDate']; ?></td>
                                        <td><?php echo e($loginData['login']); ?></td>
                                        <td><?php echo e($loginData['logout']); ?></td>
                                        <td><?php echo e($loginData['hours']); ?></td>
                                        <td><?php echo e($loginData['dailyReport']); ?></td>
                                        <td><?php echo $loginData['notes']; ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <br>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Total Hours</th>
                                <th>Total Hours(%)</th>
                                <th>Total Tasks Logged</th>
                                <th>Daily Reports</th>
                                <th>Daily Reports(%)</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            <tr>
                                <?php if($data['tasks']->count('date')): ?>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->sum('hours')); ?></td>
                                    <td><?php echo e(number_format((($data['tasks']->where('dailyReport', 'Yes')->sum('hours') / ($data['tasks']->count('date') * 8)) * 100), 2)); ?></td>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->sum('taskCount')); ?></td>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->count()); ?></td>
                                    <td><?php echo e(number_format((($data['tasks']->where('dailyReport', 'Yes')->count() / ($data['tasks']->count('date'))) * 100), 2)); ?></td>
                                <?php else: ?>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->sum('hours')); ?></td>
                                    <td>0.00</td>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->sum('taskCount')); ?></td>
                                    <td><?php echo e($data['tasks']->where('dailyReport', 'Yes')->count()); ?></td>
                                    <td>0.00</td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <?php if(count($data['rejectedTasks'])): ?>
                        <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("work_summary",$employee['sections']))): ?>
                            <div style="background-color: lightgrey;">
                                <h4>Rejected Tasks</h4>
                            </div>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th width="18%">Date</th>
                                        <th>Tasks</th>
                                        <th>Sign In time</th>
                                        <th>Sign off time</th>
                                        <th>Hours</th>
                                        <th>Details</th>
                                        <th>Present</th>
<!--                                        <th>Observations</th>-->
                                    </tr>
                                </thead>
                                <tbody style="font-size: small">
                                    <?php
                                        $sNo = 1;
                                    ?>
                                    <?php $__currentLoopData = $data['rejectedTasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejectedTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sNo++); ?></td>
                                            <td><?php echo $rejectedTask['date']; ?></td>
                                            <td><?php echo e($rejectedTask['name']); ?></td>
                                            <td><?php echo e($rejectedTask['entryTime']); ?></td>
                                            <td><?php echo e($rejectedTask['exitTime']); ?></td>
                                            <td><?php echo e($rejectedTask['hours']); ?></td>
                                            <td><?php echo e($rejectedTask['details']); ?></td>
                                            <td><?php echo e(($rejectedTask['hours'] == 0 || $rejectedTask['hours'] == "No hours specified") ? 'No' : 'Yes'); ?></td>
<!--                                            <th><?php echo e(($rejectedTask['name'] == "No task name" || $rejectedTask['hours'] == "No hours specified" || $rejectedTask['hours'] == 0) ? 'Yes' : 'No'); ?></th>-->
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>
                    <hr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($employee['personalData']['machine_ips'])): ?>
                <?php if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("ip_summary",$employee['sections']))): ?>
                    <div style="background-color: lightgrey;">
                        <h4>IP summary</h4>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>IP</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            <?php
                                $sNo = 1;
                            ?>
                            <?php $__currentLoopData = $employee['personalData']['machine_ips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine_ips): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($sNo++); ?></td>
                                    <td><?php echo e($machine_ips->machine_ip); ?></td>
                                    <td><?php echo e($machine_ips->total); ?></td>
                                    <td><?php echo e((in_array($machine_ips->machine_ip, $employee['ips'])) ? 'Yes' : 'No'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
            <hr>
            </div><div class="page-break"></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></body></html>

<?php /**PATH /var/www/vhosts/db-preprod.com360degree.com/httpdocs/resources/views/reports/pdf.blade.php ENDPATH**/ ?>