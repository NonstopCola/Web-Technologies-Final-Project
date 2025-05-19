<?php
session_start();
require_once './settings.php';
$activePage = 'jobs';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./styles.css">
    <?php include './header.inc'; ?>
    <title>Available Jobs</title>
</head>
<body>
    <div class="logoContainer">
        <img src="./images/final_logo.png" alt="logo of company" id="Logo">
    </div>

    <h1>Available Jobs</h1>

    <?php include './nav.inc'; ?>

    <div id="main">
        <aside id="aside">
            <p>Applicants for all jobs must be willing to work in office at least 3 days a week</p>
        </aside>

        <?php
        // Create jobs table if it does not exist
        $create_table = "CREATE TABLE IF NOT EXISTS `jobs` (
            `title` varchar(50) NOT NULL PRIMARY KEY,
            `reference_number` varchar(5) NOT NULL,
            `summary` text NOT NULL,
            `responsibilities` text NOT NULL,
            `essential` text NOT NULL,
            `preferred` text NOT NULL,
            `report` varchar(30) NOT NULL,
            `salary` varchar(10) NOT NULL
        )";

        if (!mysqli_query($conn, $create_table)) {
            echo "<p>Error creating jobs table: " . mysqli_error($conn) . "</p>";
        }

        // Check if jobs are present
        $sql = "SELECT * FROM jobs ORDER BY title";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            // insert jobs into table if empty
            $pre_job = [
                [
                    'title' => "Network Administrator",
                    'reference_number' => "K9986",
                    'summary' => "We are seeking a skilled and detail-oriented Network Administrator to manage, maintain, and optimize our organization's network infrastructure.",
                    'responsibilities' => "<ol><li>Install, configure, and maintain network hardware and software.</li><li>Monitor network performance and troubleshoot issues as they arise.</li><li>Implement security measures to protect the network from unauthorized access.</li><li>Manage and maintain firewalls, VPNs, and intrusion detection systems.</li><li>Collaborate with IT teams to support network-related projects and initiatives.</li><li>Document network configurations, procedures, and policies.</li><li>Provide technical support to end-users regarding network-related issues.</li></ol>",
                    'essential' => "<ul><li>Bachelor's degree in Computer Science, Information Technology, or a related field.</li><li>5 Years of proven experience as a Network Administrator or similar role.</li><li>Experience with network security practices and tools.</li><li>Familiarity with firewalls, VPNs, and intrusion detection systems.</li><li>Strong troubleshooting and problem-solving skills.</li><li>Excellent communication and interpersonal abilities.</li><li>High level understanding of the coding language Python.</li><li>High level understanding of the coding language Perl.</li></ul>",
                    'preferred' => "<ul><li>Experience with network monitoring tools (e.g., Wireshark, SolarWinds).</li><li>Ability to work in a fast-paced environment and manage multiple tasks.</li><li>Ability to excel when working from home and or being alone.</li></ul>",
                    'report' => "Direct report - Lead Network Administrator",
                    'salary' => "90,000 - 110,000"
                ],
                [
                    'title' => "Software Developer",
                    'reference_number' => "J7652",
                    'summary' => "We are looking for a knowledgeable and proactive Software Developer to join our team.",
                    'responsibilities' => "<ol><li>Design, develop, and maintain software applications.</li><li>Write clean, scalable, and efficient code.</li><li>Debug and troubleshoot software issues.</li><li>Participate in code reviews and provide constructive feedback.</li><li>Stay updated with emerging technologies and industry trends.</li></ol>",
                    'essential' => "<ul><li>Bachelor's degree in Computer Science, Information Technology, or a related field.</li><li>5 Years of proven experience as a Software Developer or similar role.</li><li>Experience with multiple coding languages such as python, c++ and java.</li><li>Strong troubleshooting and problem-solving skills.</li><li>Excellent communication and interpersonal abilities.</li></ul>",
                    'preferred' => "<ul><li>Strong portfolio of prior projects in the field.</li><li>Ability to work in a fast-paced environment and manage multiple tasks.</li><li>Ability to excel when working from home and or being alone.</li></ul>",
                    'report' => "Direct report - Lead Software Developer",
                    'salary' => "80,000 - 100,000"
                ],
                [
                    'title' => "Cybersecurity Specialist",
                    'reference_number' => "S9475",
                    'summary' => "We are seeking a highly skilled Cybersecurity Specialist to protect systems from cyber threats.",
                    'responsibilities' => "<ol><li>Monitor network traffic for suspicious activity and potential threats.</li><li>Conduct vulnerability assessments and penetration testing.</li><li>Implement security measures to protect sensitive data and systems.</li><li>Respond to security incidents and breaches, conducting investigations as needed.</li><li>Develop and maintain security policies, procedures, and documentation.</li><li>Provide training and support to staff on cybersecurity best practices.</li></ol>",
                    'essential' => "<ul><li>Bachelor's degree in Cybersecurity, Information Technology, or a related field.</li><li>5 Years of proven experience as a Cybersecurity Specialist or similar role.</li><li>Experience with security tools and technologies (e.g., firewalls, intrusion detection systems).</li><li>Strong understanding of network protocols and security practices.</li></ul>",
                    'preferred' => "<ul><li>Ability to work in a fast-paced environment and manage multiple tasks.</li><li>Ability to excel when working from home and or being alone.</li><li>Excellent analytical and problem-solving skills.</li><li>Strong communication and interpersonal abilities.</li></ul>",
                    'report' => "Direct report - Lead Cybersecurity Specialist",
                    'salary' => "115,000 - 168,000"
                ]
            ];

            $stmt = mysqli_prepare($conn, "INSERT INTO jobs (title, reference_number, summary, responsibilities, essential, preferred, report, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                foreach ($pre_job as $job) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssssss",
                        $job['title'],
                        $job['reference_number'],
                        $job['summary'],
                        $job['responsibilities'],
                        $job['essential'],
                        $job['preferred'],
                        $job['report'],
                        $job['salary']
                    );
                    if (!mysqli_stmt_execute($stmt)) {
                        echo "<p>Error inserting job: " . mysqli_stmt_error($stmt) . "</p>";
                    }
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Prepare failed: " . mysqli_error($conn) . "</p>";
            }

            $result = mysqli_query($conn, $sql); // Re-fetch after inserting
        }

        if (!$result) {
            echo "<p>Error fetching jobs: " . mysqli_error($conn) . "</p>";
        } elseif (mysqli_num_rows($result) == 0) {
            echo "<p>No jobs available at the moment.</p>";
        } else {
        ?>
        <table class="jobs-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Reference Number</th>
                    <th>Summary</th>
                    <th>Responsibilities</th>
                    <th>Essential Qualifications</th>
                    <th>Preferred Qualifications</th>
                    <th>Direct Report</th>
                    <th>Salary Range</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reference_number']) . "</td>";
                    echo "<td class='summary'>" . nl2br(htmlspecialchars($row['summary'])) . "</td>";
                    echo "<td class='responsibilities'>" . $row['responsibilities'] . "</td>";
                    echo "<td class='essential'>" . $row['essential'] . "</td>";
                    echo "<td class='preferred'>" . $row['preferred'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['report']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php } ?>

        <?php include './footer.inc'; ?>
    </div>
</body>
</html>
