<?php

class StudentManager
{
    private $file;

    public function __construct($file = 'students.json')
    {
        $this->file = $file;

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    private function readData()
    {
        return json_decode(file_get_contents($this->file), true);
    }

    private function writeData($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
    //Generate ID
    private function generateId()
     {
         $students = $this->readData();

     if (empty($students)) {
            return 1;
         }

        $ids = array_column($students, 'id');
     return max($ids) + 1;
    }

    public function getAllStudents()
    {
        return $this->readData();
    }

    public function getStudentById($id)
    {
        $students = $this->readData();

        foreach ($students as $student) {
            if ($student['id'] == $id) {
                return $student;
            }
        }
        return null;
    }

    public function create($data)
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        $students = $this->readData();

        $data['id'] = $this->generateId(); // ✅ INTEGER ID
        $data['id'] = (int) $data['id'];

        $students[] = $data;
        $this->writeData($students);

        return true;
    }

    public function update($id, $data)
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        $students = $this->readData();

        foreach ($students as &$student) {
            if ($student['id'] == $id) {
                $student = array_merge($student, $data);
                $this->writeData($students);
                return true;
            }
        }

        return "Student not found";
    }

    public function delete($id)
    {
        $students = $this->readData();

        foreach ($students as $key => $student) {
            if ($student['id'] == $id) {
                unset($students[$key]);
                $this->writeData(array_values($students));
                return true;
            }
        }

        return "Student not found";
    }
}

