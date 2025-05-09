-- Create Companies table
CREATE TABLE companies (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  description TEXT,
  admin_id INTEGER NOT NULL,
  logo_url TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- Add company_id to courses table
ALTER TABLE courses ADD COLUMN company_id INTEGER;
ALTER TABLE courses ADD FOREIGN KEY (company_id) REFERENCES companies(id);

-- Add company_id to challenges table
ALTER TABLE challenges ADD COLUMN company_id INTEGER;
ALTER TABLE challenges ADD FOREIGN KEY (company_id) REFERENCES companies(id);

-- Add company_id to rewards table
ALTER TABLE rewards ADD COLUMN company_id INTEGER;
ALTER TABLE rewards ADD FOREIGN KEY (company_id) REFERENCES companies(id);

-- Update users table to include company_id for employees
ALTER TABLE users ADD COLUMN company_id INTEGER;
ALTER TABLE users ADD FOREIGN KEY (company_id) REFERENCES companies(id);