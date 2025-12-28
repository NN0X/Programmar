CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    ram INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'code',
    total_lessons INT DEFAULT 20,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_courses (
    user_id INT REFERENCES users(id),
    course_id INT REFERENCES courses(id),
    completed_lessons INT DEFAULT 0,
    last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, course_id)
);

INSERT INTO courses (title, description, icon, total_lessons) VALUES
('Python', 'Start your journey with Python.', 'python', 20),
('JavaScript', 'Learn the language of the web.', 'javascript', 25),
('Java', 'Maybe build next Minecraft.', 'java', 30),
('C++', 'See why more is not always better.', 'cpp', 45),
('C', 'Learn the best language there is.', 'c', 30)
ON CONFLICT DO NOTHING;
