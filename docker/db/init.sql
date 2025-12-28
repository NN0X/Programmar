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
('Python', 'Start your journey with Python.', 'python'),
('JavaScript', 'Learn the language of the web.', 'javascript'),
('Java', 'Maybe build next Minecraft.', 'java'),
('C++', 'See why more is not always better.', 'cpp'),
('C', 'Learn the best language there is.', 'c')
ON CONFLICT DO NOTHING;
