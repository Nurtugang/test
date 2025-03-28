<?php
class Student {
	public $studentid;
	public $con;

	public function img() {
		if (file_exists('dist/img/student/'.$this->studentid.'.jpg')) {
			return 'dist/img/student/'.$this->studentid.'.jpg';
		} else {
			return 'dist/img/student/blank.jpg';
		}
	}
	public function data($data) {
		$sql = 'select '.$data.' from students where studentid = '.$this->studentid;
		$res = $this->con->query($sql);
		return mysqli_fetch_row($res);
	}
	public function gpa() {
		$sql = "select GET_GPAL(".$this->studentid.",0)";
		$res = $this->con->query($sql);// or return $sql;
		list($gpa) = mysqli_fetch_row($res);
		return $gpa;
	}
	public function credits() {
		$sql = "select GET_CREDITSL(".$this->studentid.")";
		$res = $this->con->query($sql);
		list($credits) = mysqli_fetch_row($res);
		return $credits;
	}
	public function groupname($groupid) {
		$sql = "select name from groups where groupid=$groupid";
		$res = $this->con->query($sql);// or die($con->error);
		list($groupname) = mysqli_fetch_row($res);
		return $groupname;
	}
	public function professionname($professionID,$lang) {
		$sql = "select professionName$lang from professions where professionID=$professionID";
		$res = $this->con->query($sql);// or die($con->error);
		list($professionID) = mysqli_fetch_row($res);
		return $professionID;
	}
	public function fio() {
		$sql = "select lastname,firstname,patronymic from students where studentid=".$this->studentid;
		$res = $this->con->query($sql);
		list($s,$n,$p) =mysqli_fetch_row($res);
		return "$s $n $p";
	}
	public function concatfio() {
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from students where studentid=".$this->studentid;
		$res = $this->con->query($sql);
		list($fio) =mysqli_fetch_row($res);
		return "$fio";
	}
	public function studentdata($data) {
		$sql = "select ProfessionID,CourseNumber,groupID,lastname,firstname,patronymic from students where studentid=".$this->studentid;
		$res = $this->con->query($sql);
		$rez = mysqli_fetch_row($res);
		return $rez;
	}
	public function gradejournal($studygroupid,$markid,$number) {
		$sql = "select mark from journal where studygroupid=$studygroupid and marktypeid=$markid and number=$number and iscurrent=1 and studentid=".$this->studentid;
		$res = $this->con->query($sql);
		if ($res->num_rows==0) {
			return '---';
		} else {
			list($mark) = mysqli_fetch_row($res);
			return $mark;
		}
	}
	public function reiting ($studygroupid) {
		$sql = "select ap_ratings from totalmarks where studygroupid = $studygroupid and is current = 1 and studentid = ".$this->studentid;
		$res = $this->con->query($sql);
		if ($res->num_rows==0) {
			return '---';
		} else {
			list($mark) = mysqli_fetch_row($res);
			return $mark;
		}
	}
}
class Tutor {
	public $tutorid;
	public $con;
	public function img() {
		if (file_exists('dist/img/pps/'.$this->tutorid.'.jpg')) {
			return 'dist/img/pps/'.$this->tutorid.'.jpg';
		} else {
			return 'dist/img/student/blank.jpg';
		}
	}
	public function tutordata() {
		$sql = "SELECT scientificdegree.NAMERU AS stepen, academicstatus.nameru AS zvan, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors INNER JOIN academicstatus ON tutors.AcademicStatusID = academicstatus.id INNER JOIN scientificdegree ON scientificdegree.ID = tutors.ScientificDegreeID WHERE tutors.TutorID = ".$this->tutorid;
		$res = $this->con->query($sql);
		return mysqli_fetch_row($res);
	}
	public function iin() {
		$sql = "select iinplt from tutors where tutorid=".$this->tutorid;
		$res = $this->con->query($sql);
		list($iin) =mysqli_fetch_row($res);
		return "$iin";
	}
	public function concatfio() {
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from tutors where tutorid=".$this->tutorid;
		$res = $this->con->query($sql);
		list($fio) =mysqli_fetch_row($res);
		return "$fio";
	}
	public function fio() {
		$sql = "select concat(lastname,' ',firstname,' ',patronymic) from tutors where tutorid=".$this->tutorid;
		$res = $this->con->query($sql);
		list($fio) =mysqli_fetch_row($res);
		return "$fio";
	}
}
class Author {
	public $tutorid;
	public $con;
	public function concatfio($iin) {
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from tutors where tutorid=".$this->tutorid;
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from users where iin = '$iin'";
		$res = $this->con->query($sql);
		list($fio) =mysqli_fetch_row($res);
		return "$fio";
	}
	public function fio($iin) {
		$sql = "select concat(lastname,' ',firstname,' ',patronymic)  from users where iin = '$iin'";
		$res = $this->con->query($sql);
		list($fio) =mysqli_fetch_row($res);
		return "$fio";
	}
}
