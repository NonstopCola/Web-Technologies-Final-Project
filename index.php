<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include './header.inc'
        ?>
        <!-- Key Meta Data -->
        <title>Homepage</title>
        <!-- Titled Page -->
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>JKTN Homepage</h1>
        <?php
            include './nav.inc';
        ?>
        <!-- Added navigation bar that contains links to the 3 other pages and email -->
        <div class="homeContent">
            <div class="background">
                <h2>Welcome to JKTN!</h2>
                <!-- Added a description of the company generated with chat gpt using the prompt write a description for a company that specialises in web development-->
                <p>
                At JKTN, were passionate about helping businesses thrive in the digital world through powerful, high-performing web development solutions. As a forward-thinking IT company, we specialize in designing and developing <span class ="highlight">custom websites</span>,<span class ="highlight"> web applications</span>, and <span class ="highlight">eCommerce platforms</span> that combine innovative technology with seamless user experiences.
                </p>

                <p>
                Our team is made up of talented developers, creative designers, and strategic thinkers who work collaboratively to bring your vision to life. We believe that great web development isn’t just about writing code—it’s about <span class ="highlight">understanding your business goals</span>, your users, and the journey that connects them. That’s why every project at JKTN begins with a deep dive into your needs, followed by a <span class ="highlight">tailor-made solution</span> that’s built to scale and evolve with your business.
                </p>

                <p>
                Whether you’re launching a new startup, refreshing your brand’s online presence, or building a sophisticated platform from the ground up, JKTN delivers solutions that are <span class ="highlight">fast</span>, <span class ="highlight">secure</span>, <span class ="highlight">responsive</span>, and <span class ="highlight">optimized for performance </span>. From front-end design to back-end development, we use the latest technologies and industry best practices to ensure every project not only looks great but functions flawlessly.
                </p>

                <p>
                Beyond development, we offer ongoing support, maintenance, and optimization services to keep your digital presence performing at its best. We also place a strong emphasis on collaboration and transparency, keeping you involved and informed every step of the way.
                </p>

                <p class="missionStatement">
                At JKTN, our mission is simple: to help businesses harness the power of the web to drive growth, increase engagement, and stand out in a competitive market. With a focus on quality, innovation, and client satisfaction, we are your trusted technology partner for the long term.
                </p>
            </div>
        </div>
        <?php
            include './footer.inc';
        ?>
    </body>
</html>