<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Starts the session and includes the header
            session_start();
            include './header.inc'
        ?>
        <title>About Page</title>
        <!-- Titled Page -->
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>About Us</h1>
        <?php
            $activePage = 'about';
            // Set the active page for navigation highlighting
            include './nav.inc';
        ?>
    <div class="aboutContainer">
        <div class="about_intro">
            <div class="intro">
                <section>
                    <h2>Introduction</h2>
                    <p>
                        At JKTN, we strive to innovate and help amazing talents bring their creative visions to life. We believe that a positive, inclusive workplace culture is the foundation of great work — and we’re committed to fostering an environment that our team loves being part of.
                        Founded on March 24th this year, we’re a passionate and tight-knit team of four. Despite being a young company, our ambitions are big. Every team member plays a vital role in shaping our direction, and we take pride in collaborating, learning, and evolving together.
                        We’re always on the lookout for fresh talent — driven individuals who are ready to make an impact. If you think you have the creativity, skills, and passion to contribute to the JKTN journey, we’d love to hear from you. Head over to our apply page and show us what makes you unique.
                        Your future at JKTN could start today. let’s build something incredible together.
                    </p>
                </section>
                </div>
                    <section id="aboutJKTN">
                    <h2>About JKTN</h2>
                    <!-- Setup headings and a basic description-->
                    <p>
                        Here we list the current ID's of our members and JKTN's overlapping class times.
                    </p>

                    <ul>
                        <li class="Member">JKTN
                            <ul>
                                <li>Monday 12:30-2:30</li>
                            </ul>
                        </li>
                        <!-- Created unordered list for JKTN then a nested unorder listed for the class schedule -->

                        <li class="Member">Jake Hardy
                            <ul>
                                <li class="StudentID">ID: 105921038</li>
                            </ul>
                        </li>
                        <li class="Member">Kyle Perry
                            <ul>
                                <li class="StudentID">ID: 105911291</li>
                            </ul>
                        </li>
                        <li class="Member">Tate Newman
                            <ul>
                                <li class="StudentID">ID: 105750274</li>
                            </ul>
                        </li>
                        <li class="Member">Nathan Rohner
                            <ul>
                                <li class="StudentID">ID: 105620335</li>
                            </ul>
                        </li>
                        <!-- Added entries for each members names, setting a class for styles.css of "Member", 
                        then added nested list with Student ID, set "StudentID" -->
                    </ul>

                    <p>
                        Here we list our current supervisor who will be guiding us through our learning and this project.
                    </p>

                    <dl>
                        <dt class="Tutor">Tutor</dt>
                        <dd class="TutorName">Enrique Ketterer Ortiz</dd>
                        <!-- Added definition list with Tutor as a title and the tutor's name as the subject with class's set to both as it 
                        shares the same tags as the responsibilites list -->
                    </dl>
                    </section>
            </div>
            <div class="responsibilities_image">
                <section id="responsibilities">
                <h2>Responsibilities</h2>

                <p>
                    Here we list the members and their corresponding responsibilities as a part of JKTN.
                </p>

                <dl>
                    <dt>Jake Hardy</dt>
                    <dd>Team Leader, About Page, Planning, Index Page, Enhancements Page, Settings, Include Files</dd>
                    <dt>Kyle Perry</dt>
                    <dd>Jobs Page, Index Page, Updating Job Descriptions</dd>
                    <dt>Tate Newman</dt>
                    <dd>CSS, Index Page, MySQL, Expressions of Interest</dd>
                    <dt>Nathan Rohner</dt>
                    <dd>Application Page, Index Page, Manage Page</dd>
                    <!-- Added definition list that lists out the responsibilities of each member of JKTN -->
                </dl>
                </section>
                <section id="team_image">
                <h2>Our Members</h2>
                <figure>
                    <!-- The Image was set to a 432x188 pixel limit, can be removed for css changes if need be -->
                    <a href="./images/group.jpg" target="_blank"><img src="./images/group_low.jpg" alt="The JKTN team" 
                        loading="lazy" title="The JKTN team, we look forward to working with you!" width="550" height="220" id="teamimage"></a>
                    <!-- Created an image referencing ./image/group_low.png for it's source, the image needs to be less than 300kb,
                    but it is referencing the original image -->
                    <figcaption>The JKTN team!</figcaption>
                    <!-- Added caption for the image that appears below it -->
                </figure>
                </section>
            </div>
            <section id="interests">
            <h3>Our Interests</h3>

            <p>
                A list of our group members interests and basic information.
            </p>
            <table>
                <tr>
                    <th>Members</th>
                    <th>Hometown</th>
                    <th>Age</th>
                    <th>Course</th>
                    <th>Favourite Movies</th>
                    <th>Favourite Books</th>
                    <th>Other</th>
                </tr>
                <tr>
                    <td>Jake Hardy</td>
                    <td>Point Cook</td>
                    <td>18</td>
                    <td>Computer Science (Professional)</td>
                    <td>A Man Called Otto</td>
                    <td>Overlord</td>
                    <td>I enjoy walking my dogs and grinding Marvel Rivals</td>
                </tr>
                <tr>
                    <td>Kyle Perry</td>
                    <td>Point cook</td>
                    <td>19</td>
                    <td>Computer Science (Professional)</td>
                    <td>50 First dates</td>
                    <td>Talon</td>
                    <td>addicted to coke (the drink).</td>
                </tr>
                <tr>
                    <td>Tate Newman</td>
                    <td>Templestowe</td>
                    <td>19</td>
                    <td>Computer Science (Software Development)</td>
                    <td>The Notebook, Prisoners, La La Land, Interstellar</td>
                    <td>An Actor Prepares</td>
                    <td>I enjoy going to the gym and dialing in my fitness, playing story open-world video games and hanging out with my girlfriend & mates </td>
                </tr>
                <tr>
                    <td>Nathan Rohner</td>
                    <td>Somerville</td>
                    <td>27</td>
                    <td>Computer Science (Software Development)</td>
                    <td>Empire Strikes Back</td>
                    <td>Blood, Sweat and Pixels</td>
                    <td>KFC is love, KFC is life.</td>
                </tr>
            </table>
            <!-- Created a table listing each members interests, general info and any other info they'd like to share -->
            </section>
            <?php
                include './footer.inc';
            ?>
    </div>
    </body>
</html>