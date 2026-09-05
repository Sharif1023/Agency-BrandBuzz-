-- BrandBuzz Agency · MySQL 8+ / MariaDB 10.6+
-- Create and select your database in phpMyAdmin before importing this file.
-- This file does NOT drop existing tables or create a default admin password.
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin') NOT NULL DEFAULT 'admin',
 session_version INT UNSIGNED NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS services (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(140) NOT NULL,
 slug VARCHAR(160) NOT NULL UNIQUE,
 excerpt VARCHAR(300) NOT NULL,
 content MEDIUMTEXT NOT NULL,
 icon VARCHAR(30) NOT NULL DEFAULT 'sparkles',
 accent VARCHAR(20) NOT NULL DEFAULT 'orange',
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 sort_order INT UNSIGNED NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_services_status_sort (status,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS projects (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(140) NOT NULL,
 slug VARCHAR(160) NOT NULL UNIQUE,
 excerpt VARCHAR(300) NOT NULL,
 content MEDIUMTEXT NOT NULL,
 category VARCHAR(60) NOT NULL,
 client VARCHAR(100) NOT NULL,
 project_year SMALLINT UNSIGNED NOT NULL,
 image VARCHAR(255) DEFAULT NULL,
 website_url VARCHAR(500) NOT NULL DEFAULT '',
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 sort_order INT UNSIGNED NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_projects_status_sort (status,sort_order),
 INDEX idx_projects_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS blogs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(140) NOT NULL,
 slug VARCHAR(160) NOT NULL UNIQUE,
 excerpt VARCHAR(300) NOT NULL,
 content MEDIUMTEXT NOT NULL,
 category VARCHAR(60) NOT NULL,
 author VARCHAR(100) NOT NULL,
 image VARCHAR(255) DEFAULT NULL,
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 sort_order INT UNSIGNED NOT NULL DEFAULT 0,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_blogs_status (status,id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS contacts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL,
 phone VARCHAR(40) NOT NULL DEFAULT '',
 service VARCHAR(140) NOT NULL DEFAULT '',
 budget VARCHAR(100) NOT NULL DEFAULT '',
 message TEXT NOT NULL,
 status ENUM('new','read','archived') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_contacts_status_date (status,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO services (`id`,`title`,`slug`,`excerpt`,`content`,`icon`,`accent`,`status`,`sort_order`) VALUES
(1,'SEO & SEM','seo-sem','Be found when it matters. Bring the right people to your website through search.','Make your business easier to discover. We start by understanding what your audience is searching for, how your website is organised and where useful opportunities exist.

Our work can include a technical website review, keyword research, search-friendly page content and clear recommendations for your team. For paid search, we help shape campaign structure, landing pages and measurement around an agreed budget.

You receive a practical plan with priorities, ownership and reporting. Search performance depends on competition, your offer and ongoing work; we focus on measurable improvements rather than guaranteed rankings.

Tell us about your website and the customers you want to reach. We will recommend a scope that fits your goals.','search','yellow','published',1),
(2,'Digital marketing','digital-marketing','Connect with your audience through campaigns that have something meaningful to say.','Show up with a message that makes sense for your audience. We help you choose the channels, content and campaign structure that fit your business.

We begin with a discovery conversation, an audience review and a look at your existing marketing. From there, we build a focused plan with clear creative direction, a content calendar and a measurement approach.

Our scope can include social media planning, campaign copy, creative assets, paid campaign setup and reporting. Media budgets and third-party platform charges are agreed separately from creative work.

At each stage, you will know what is being made, when it will be ready and how to share feedback.','megaphone','green','published',2),
(3,'Brand & creative','brand-creative','Build a recognisable brand with thoughtful identity, storytelling and creative direction.','Give your brand a clear and consistent voice. We work with you to understand your positioning, the people you serve and the impression you want to leave.

A brand project may include a discovery workshop, a positioning statement, visual direction, logo design, a colour palette, typography and practical usage guidelines. Deliverables are agreed before work begins.

We share creative directions, explain the thinking behind them and refine the selected approach together. The result is a useful identity system that your team can apply with confidence.

Already have a brand? We can help bring it to life through campaign design, presentation templates and digital content.','sparkles','purple','published',3),
(4,'Web design','web-design','Turn first impressions into better experiences with clear, responsive websites.','Create a website that helps people understand your business and take the next step. We connect a clear content structure with thoughtful design and practical development.

We map the main pages and user journeys, create a visual direction, and build a responsive experience for mobile, tablet and desktop. Your team reviews the content and design as the work takes shape.

Depending on the project, we can include an editable content system, enquiry forms, portfolio pages and basic search metadata. Integrations, hosting and ongoing maintenance are scoped separately.

Before handover, we check the key journeys and provide guidance for managing your content. Tell us what you need your website to do, and we will help define the right starting point.','code','orange','published',4);

INSERT IGNORE INTO projects (`id`,`title`,`slug`,`excerpt`,`content`,`category`,`client`,`project_year`,`image`,`website_url`,`status`,`sort_order`) VALUES
(1,'Onda — a fresh perspective','onda-brand-concept','A warm, approachable brand direction for a concept lifestyle business.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Brand strategy','Onda · Concept',2026,'images/hero.webp','','published',1),
(2,'The Everyday Edit','everyday-edit-campaign','An editorial campaign concept that puts useful stories at the centre.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Digital marketing','Everyday · Concept',2026,'images/process.webp','','published',2),
(3,'Folio — room to grow','folio-digital-experience','A clear digital experience for a concept studio with ambitious ideas.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Web design','Folio · Concept',2026,'images/agency.webp','','published',3),
(4,'Bright Side stories','bright-side-stories','A social content direction built around simple, positive everyday moments.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Digital marketing','Bright Side · Concept',2026,'images/hero.webp','','published',4),
(5,'Local & lovely','local-lovely-identity','A friendly identity direction for a neighbourhood business concept.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Brand strategy','Local & Lovely · Concept',2026,'images/process.webp','','published',5),
(6,'A clearer first impression','clearer-first-impression','A website planning concept focused on navigation, content and enquiry flow.','This is a sample concept project included with the BrandBuzz template. It demonstrates the case-study page and is not a claim of client work or measured results.

The brief
Create a clearer way for a growing business to explain its offer, connect with its audience and build a consistent first impression.

The approach
Start with the audience and the message. Develop a focused creative direction, organise the content and bring the most useful ideas into a coherent experience.

The deliverables
A sample strategy outline, creative direction and an example content structure. Replace this story, the cover image and project details with your own work using the admin panel.','Web design','North Studio · Concept',2026,'images/agency.webp','','published',6);

INSERT IGNORE INTO blogs (`id`,`title`,`slug`,`excerpt`,`content`,`category`,`author`,`image`,`status`,`sort_order`) VALUES
(1,'A better brief makes better work','a-better-creative-brief','Before the moodboards and mockups, a few clear answers can give your project a much stronger start.','A useful creative brief does not have to be a long document. It needs to explain the problem, the audience and what success would look like. Start with what you want people to understand or do after seeing the finished work.

Describe the people you want to reach. What do they already know? What gets in their way? What might make them choose you? Specific observations are more useful than broad labels like “everyone” or “young people”.

Share the practical limits early. Your budget, deadline, approval process and existing assets all shape the work. If several people need to approve a direction, agree who makes the final decision before the project starts.

Collect a few relevant references and explain what you like about each one. A reference can help communicate tone, layout or energy, but it should leave room for a direction that belongs to your business.

Finally, define the deliverables. A logo, a set of social templates and a full identity guide are different jobs. Agreeing the scope gives everyone a clearer foundation for useful, focused creative work.','Brand strategy','BrandBuzz Editorial','images/hero.webp','published',1),
(2,'What should your homepage actually say?','what-your-homepage-should-say','A simple way to organise your homepage around the questions your visitors already have.','Your homepage is often the first conversation someone has with your business. Start by making your offer clear. A visitor should be able to understand what you do and who it is for without decoding a clever slogan.

Follow that introduction with the services or products that matter most. Use familiar language and describe the benefit in concrete terms. Instead of trying to fit every detail on the homepage, give people a useful path to the next page.

Show evidence where you have it. A real project, a genuine client quote or a clear explanation of your process can help people evaluate your work. Avoid adding numbers or promises you cannot support.

Make the next step easy to find. Your main action might be to enquire, book a conversation or explore a service. Use a label that tells people what happens when they click.

Before publishing, read the page on a phone. Check the order of the content, the length of paragraphs and whether the main links remain easy to use. A clear mobile experience is a good test of your content priorities.','Web design','BrandBuzz Editorial','images/agency.webp','published',2),
(3,'A content calendar you can keep up with','a-practical-content-calendar','Build a realistic publishing rhythm around useful ideas, your audience and the time you actually have.','A content calendar should help you make decisions, not become another task you cannot finish. Start with a publishing rhythm your team can maintain. A few useful posts are a better starting point than an ambitious schedule that quickly becomes difficult to manage.

Choose a small number of themes related to your audience and your offer. These could include common questions, examples of your work, practical advice and a look at how your team works. Give each theme a clear purpose.

Plan enough detail to make production easier. A working title, a short outline, an owner and a publication date are often enough. Add the format and any assets you need so that the next step is obvious.

Leave room for new information. A calendar is a plan, not a promise to publish an idea that no longer makes sense. Review it regularly and adjust the topics using feedback from your audience.

Track the responses that relate to your goals. Useful conversations, relevant enquiries and repeat interest can tell you more than a single post with a high impression count.','Digital marketing','BrandBuzz Editorial','images/process.webp','published',3),
(4,'Before you launch your next campaign','before-your-next-campaign','Five practical checks to connect your message, landing page and follow-up before going live.','Start by checking that the offer is specific. What is available, who is it for and what does the person need to do? Keep the main message consistent across the campaign and the page it links to.

Visit the landing page on a phone. Check that the content loads, the text is readable and the main action is easy to find. If you use a form, submit a test enquiry and confirm that your team can find it.

Agree who will respond to enquiries. A campaign creates a new conversation only if someone is ready to continue it. Decide who owns follow-up and how the enquiry will move through your team.

Set up the measurement you need and check that it works. Use the smallest useful set of metrics tied to the campaign objective. Follow the privacy and consent requirements that apply to your business and audience.

Finally, make time to review. Decide when you will inspect the results, what would lead you to change direction and who can approve those changes. A clear review plan makes the campaign easier to manage after launch.','Digital marketing','BrandBuzz Editorial','images/hero.webp','published',4);

INSERT IGNORE INTO settings (`setting_key`,`setting_value`) VALUES
('site_name','BrandBuzz'),
('meta_description','Clear strategy, thoughtful design and digital marketing for ambitious brands. Explore BrandBuzz services, creative work and practical insights.'),
('hero_eyebrow','A little spark. A bigger impact.'),
('hero_title','We create'),
('hero_highlight','solutions'),
('hero_after','for your business.'),
('hero_description','Good ideas deserve to be seen. We bring strategy, creativity and digital know-how together to help your brand grow.'),
('hero_button','Get started'),
('hero_image','images/hero.webp'),
('process_image','images/process.webp'),
('about_title','Your next chapter.'),
('about_highlight','Our creative energy.'),
('about_description','We believe that good work begins with understanding your business. Your audience, your ambitions and the little things that make your brand different.

From first ideas to the finishing touches, we connect clear strategy with thoughtful design to build experiences that feel like you.'),
('about_image','images/agency.webp'),
('cta_title','Ready to get started?'),
('cta_description','Let’s turn your next big idea into something real.'),
('footer_text','Good ideas. Thoughtful strategy. A little buzz for your business.'),
('contact_email','hello@example.com'),
('contact_phone',''),
('contact_address','Dhaka, Bangladesh'),
('office_hours','Sunday–Thursday · 10am–6pm'),
('facebook',''),
('instagram',''),
('linkedin',''),
('show_testimonials','1'),
('testimonial_1_name','Ayesha Rahman'),
('testimonial_1_role','Founder · Sample client'),
('testimonial_1_quote','They took the time to understand our idea and helped us shape it into a clear, consistent brand direction.'),
('testimonial_2_name','Rohan Ahmed'),
('testimonial_2_role','Marketing lead · Sample client'),
('testimonial_2_quote','A thoughtful team with a clear process. We always knew what was happening and where our feedback fitted in.'),
('testimonial_3_name','Nadia Islam'),
('testimonial_3_role','Business owner · Sample client'),
('testimonial_3_quote','From the first conversation to the final details, the work felt collaborative, focused and easy to follow.');

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('demo_labels', '1');
