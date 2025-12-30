CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100),
        ram INT DEFAULT 5,
        last_ram_check TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
        id SERIAL PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        icon VARCHAR(50) DEFAULT 'code',
        is_visible BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_courses (
        user_id INT REFERENCES users(id) ON DELETE CASCADE,
        course_id INT REFERENCES courses(id) ON DELETE CASCADE,
        completed_lessons INT DEFAULT 0,
        current_lesson_status BOOLEAN DEFAULT FALSE,
        last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, course_id)
);

INSERT INTO courses (title, description, icon) VALUES
('DEBUG', 'DEBUG', 'debug', FALSE),
('Sentino', 'Sentence, Tatuażyk, Król Sento, Sebastian Enrique Alvarez Pałucki, etc.', 'sentino', FALSE),
('Python', 'Start your journey with Python.', 'python'),
('JavaScript', 'Learn the language of the web.', 'javascript', FALSE),
('Java', 'Maybe build next Minecraft.', 'java', FALSE),
('C++', 'See why more is not always better.', 'cpp', FALSE),
('C', 'Learn the best language there is.', 'c'),
('Go', 'Fast and efficient programming.', 'go', FALSE),
('Rust', 'Memory safety and performance.', 'rust', FALSE),
('Ruby', 'A programmer''s best friend.', 'ruby', FALSE),
('HTML & CSS', 'Design beautiful web pages.', 'html5', FALSE),
('SQL', 'Manage and query databases.', 'database', FALSE),
('Bash', 'Automate tasks in Unix/Linux.', 'terminal', FALSE),
('Ada', 'Programming in Ada language.', 'ada', FALSE),
('Kotlin', 'Modern programming for Android.', 'kotlin', FALSE),
('Swift', 'Developing for Apple platforms.', 'swift', FALSE),
('TypeScript', 'Typed superset of JavaScript.', 'typescript', FALSE),
('PHP', 'Server-side scripting language.', 'php', FALSE),
('Perl', 'Practical Extraction and Reporting Language.', 'perl', FALSE),
('Lua', 'Lightweight scripting language.', 'lua', FALSE),
('Haskell', 'Purely functional programming.', 'haskell', FALSE),
('x86 Assembly', 'Low-level programming.', 'assembly', FALSE),
('MATLAB', 'Numerical computing environment.', 'matlab', FALSE),
('R Programming', 'Statistical computing and graphics.', 'r-project', FALSE),
('Dart', 'Optimized for UI development.', 'dart', FALSE),
('Elixir', 'Functional, concurrent programming.', 'elixir', FALSE),
('F#', 'Functional-first language on .NET.', 'fsharp', FALSE),
('Scala', 'Combines object-oriented and functional programming.', 'scala', FALSE),
('Julia', 'High-performance numerical analysis.', 'julia', FALSE),
('Fortran', 'Scientific and numerical computing.', 'fortran', FALSE),
('COBOL', 'Business-oriented programming language.', 'cobol', FALSE),
('Lisp', 'Family of programming languages with a long history.', 'lisp', FALSE),
('Prolog', 'Logic programming language.', 'prolog', FALSE),
('Scheme', 'Minimalist dialect of Lisp.', 'scheme', FALSE),
('Groovy', 'Dynamic language for the Java platform.', 'groovy', FALSE),
('VB.NET', 'Object-oriented programming language from Microsoft.', 'vbnet', FALSE),
('Objective-C', 'General-purpose, object-oriented programming language.', 'objectivec', FALSE),
('Git & GitHub', 'Version control and collaboration.', 'github', FALSE),
('Data Structures & Algorithms', 'Fundamental concepts for coding interviews.', 'algorithm', FALSE),
('Machine Learning', 'Introduction to machine learning concepts.', 'machine-learning', FALSE),
('Web Development', 'Building blocks of web applications.', 'web', FALSE),
('Mobile App Development', 'Creating apps for Android and iOS.', 'mobile', FALSE),
('Cloud Computing', 'Understanding cloud services and architecture.', 'cloud', FALSE),
('Cybersecurity', 'Protecting systems and data.', 'security', FALSE),
('DevOps', 'Bridging development and operations.', 'devops', FALSE),
('Robotics', 'Introduction to robotics and automation.', 'robotics', FALSE),
('Game Development', 'Creating engaging video games.', 'gamepad', FALSE)
ON CONFLICT DO NOTHING;
