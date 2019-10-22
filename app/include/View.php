<?php
class View
{
	// ===== singleton =============================================
	
	protected static $instance;

	public static function engine()
	{
        if (!isset(self::$instance)) 
		{
            $c = get_called_class();
            self::$instance = new $c;
        }
        return self::$instance;
	}
		
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

	// ===== pages =============================================

	public function loginPage($login='', $alerts=[])
	{
		$this->drawHeaders();
		$this->drawLoginForm($login, $alerts);
		$this->drawFooters();
	}
	
	public function searchPage($req = [], $alerts = [])
	{
		$this->drawHeaders();
		$this->drawNavbar();
		$this->drawSearchForm($req, $alerts);
		$this->drawDataActualDateNotification();
		$this->drawFooters();
	}
	
	public function votersPage($req, $data)
	{
		$this->drawHeaders();
		$this->drawNavbar($req, $data['total'] > 100000);
		$this->drawPagination($data['page_num'], $data['page_size'], $data['total'], $req);
		$this->drawDataGrid((array)$data['results'], $req);
		$this->drawPagination($data['page_num'], $data['page_size'], $data['total'], $req);
		$this->drawDataActualDateNotification();
		$this->drawFooters();
	}
	
	public function voterPage($id, $data)
	{
		$this->drawHeaders();
		$this->drawNavbarBack();
		$this->drawVoterCard($data['voter']);
		$this->drawVoterHistory($data['history']);
		$this->drawDataActualDateNotification();
		$this->drawFooters();
	}
	

	// ===== srv ================================================

	protected function __construct()
	{
	}
	
	
	protected function log($msg)
	{
		if (!$this->verbose)
			return;
		echo "{$msg}\n";
		flush();
	}
	

	
// ===== html ================================================

public function redirect($trg)
{
	header("Location: {$trg}");
}


public function back()
{
	
?>	<script>
		function goBack() {
		  window.history.back();
		}
		document.onload(goBack());
	</script>
<?php
die();
}

public function sendCSV($dd, $fn)
{
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="' . $fn . '";');
	echo $dd;
}


public function drawLoginForm($login='', $alerts=[])
{
?>	<div class="container">
	  <div class="row mt-3">
		<div class="col-6">
			<?php foreach ($alerts as $alert) :?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php echo $alert; ?>
				</div>
			<?php endforeach; ?>	
			<form action="login.php" method="POST">
			  <div class="form-group">
				<label for="login">Login</label>
				<?php echo "<input type=\"text\" class=\"form-control\" id=\"login\" name=\"login\" placeholder=\"Enter login\" value=\"{$login}\">";?>
			  </div>
			  <div class="form-group">
				<label for="pass">Password</label>
				<input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
			  </div>
			  <button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	  </div>
    </div>
<?php
}

public function drawSearchForm($req, $alerts = [])
{
?>	<div class="container">
	  <form action="voters.php" method="GET">
		 <div class="row mt-3">
			<div class="col-12">
				<?php foreach ($alerts as $alert) :?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?php echo $alert; ?>
					</div>
				<?php endforeach; ?>	
			</div>
		  </div>
		  <div class="row mt-3">
			<div class="col">
				<h1>New York City Voter Database</h1>
			</div>
		  </div>
		  <div class="row mt-3">
			<?php $this->drawFormGroupFromReq('First_Name', $req, 6) ?>
			<?php $this->drawFormGroupFromReq('Last_Name', $req, 6) ?>
		  </div>
		  <div class="row mt-1">
			<?php $this->drawFormGroupFromReq('Apartment_Number', $req, 1) ?>
			<?php $this->drawFormGroupFromReq('House_Number', $req, 1) ?>
			<?php $this->drawFormGroupFromReq('Street_Name', $req, 6) ?>
			<?php $this->drawFormGroupFromReq('City', $req, 2) ?>
			<?php $this->drawFormGroupFromReq('Zip_Code', $req, 2) ?>
		  </div>
		  <div class="row mt-1">
			<?php $this->drawFormGroupFromReq('Election_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Assembly_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Congress_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Council_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Senate_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Civil_Court_District', $req) ?>
			<?php $this->drawFormGroupFromReq('Judicial_District', $req) ?>
		  </div>
		  <div class="row mt-1">
			<?php $this->drawFormGroupMultiselect('Political_Party', $req, 3) ?>
			<?php $this->drawFormGroupMultiselect('Future_Party', $req, 3) ?>
			<?php $this->drawFormGroupLimits('Year_Last_Voted', $req, 3) ?>
			<?php $this->drawFormGroupLimits('Times_Voted', $req, 3) ?>
		  </div>
		  <div class="row mt-1">
			<?php $this->drawFormGroupSubmit() ?>
		  </div>
	  </form>
	</div>
<?php
}

// ----- datagrid ----------

public function drawDataGrid(array $data, $req=[])
{
	if (!$data)
	{
		$this->drawNothingFound();
		return;
	}	
	
	$hh = DataMapper::votersHeaders($data);
?>	<div class="container">
	  <table class="table">
		<thead>
		  <tr>
			<?php foreach ($hh as $i=>$h) : ?>
			  <th scope="col"><?php echo $this->dataGridHeader($hh, $i, $req); ?></th>
			<?php endforeach; ?>  
		  </tr>
		</thead>
		<tbody>
		  <?php foreach (DataMapper::votersData($data) as $row) : ?>
		    <tr>
			  <?php foreach ($row as $k=>$cell) : ?>
				<?php if ($k == '#') : ?>
				  <th><?php echo $cell; ?></th>
				<?php else : ?>
				  <td><?php echo $cell; ?></td>
				<?php endif; ?>
			  <?php endforeach; ?>
		    </tr>
		  <?php endforeach; ?>
		</tbody>
	  </table>
	</div>
<?php	
}


public function dataGridHeader($hh, $f, $req)
{
	if ($f == $req['field'])
	{
		$ord = $req['direction'] == 'DESC' ? 'ASC' : 'DESC';
		$arrow = $req['direction'] == 'DESC' ? "&nbsp;&#8681;" : "&nbsp;&#8679;";
		$capt = $hh[$f] . $arrow;
	}
	else 
	{
		$ord = 'ASC';
		$capt = $hh[$f];
	}
	$sortReq = RequestMapper::getEnc($req, ['field' => $f, 'direction' => $ord]);
	$link = "<a href='voters.php?{$sortReq}'>{$capt}</a>";
	return $link;
}

public function drawNothingFound()
{
?>	<div class="container mt-4">
	  <div class="alert alert-warning" role="alert">
		0 records found
	  </div>
	</div>
<?php
}



// ----- pagination ----------

public function drawPagination($num, $size, $totalItems, $req)
{
	$isFirst = $num == 1;
	$total = $size ? ceil($totalItems / $size) : 0;
	if ($total < 2)
		return;
	$isLast = $num == $total;
	$shorten = $total >= 10;
	
	$min = 1 + $size * ($num - 1);
	$max = $size * $num;
	
?>	  
	<div class="container my-3">
	  <div class="row">
		<div class="col-3">
			Results <?php echo $min; ?> - <?php echo $max; ?> of <?php echo $totalItems; ?>
		</div> 
		<div class="col-9">
			<nav aria-label="Page navigation">
			  <ul class="pagination justify-content-end">
				<?php $this->drawPaginationLink('Previous', $isFirst, $req, $num - 1) ?>
				<?php for ($i=1; $i<=$total; $i++) : ?>
				  <?php if ($shorten && $i>2 && $i<$num-2) : ?>
					<?php $this->drawPaginationLink('...', true, $req, $num) ?>
					<?php $i = $num-3; ?>
				  <?php elseif ($shorten && $i>$num+2 && $i<$total-1) : ?>
					<?php $this->drawPaginationLink('...', true, $req, $num) ?>
					<?php $i = $total-2; ?>
				  <?php elseif ($num == $i) : ?>
					<li class="page-item active"><a class="page-link" href="#"><?php echo $i; ?></a></li>
				  <?php else : ?>
					<?php $this->drawPaginationLink($i, false, $req, $i) ?>
				  <?php endif; ?>	
				<?php endfor; ?>  
				<?php $this->drawPaginationLink('Next', $isLast, $req, $num + 1) ?>
			  </ul>
			</nav>
	    </div>	
	  </div>	
	</div>	
<?php	
}

public function drawPaginationLink($label, $disabled, $req, $n)
{
	$link = $disabled ? '#' : ('voters.php?' . RequestMapper::getEnc($req, ['page_num' => $n]));
?>
	<li class="page-item<?php if ($disabled) echo ' disabled'; ?>">
	  <?php echo "<a class=\"page-link\" href=\"{$link}\"" . ($disabled ? ' tabindex="-1" aria-disabled="true">' : '>'); ?>
	    <?php echo $label; ?>
      </a>
	</li>
<?php	
}

// ----- / datagrid ----------

// ----- voter page ----------

public function drawVoterCard($dd)
{
?>	<div class="container pl-4">
	  <div class="row mt-3">
		 <?php $this->drawCardGroupOpen('Name', 8); ?>
			 <?php $this->drawCardField($dd, 'Last_Name', 3); ?>
			 <?php $this->drawCardField($dd, 'First_Name', 2); ?>
			 <?php $this->drawCardField($dd, 'Middle_Initial', 2); ?>
			 <?php $this->drawCardField($dd, 'Name_Suffix', 5); ?>
		 <?php $this->drawCardGroupClose(); ?>
		 
		 <?php $this->drawCardGroupOpen('Contact', 4); ?>
			 <?php $this->drawCardField($dd, 'Telephone', 12); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>
	  
	  
	  <div class="row mt-3">
		 <?php $this->drawCardGroupOpen('Party', 12); ?>
			 <?php $this->drawCardField($dd, 'Political_Party', 2); ?>
			 <?php $this->drawCardField($dd, 'Other_Party', 2); ?>
			 <?php $this->drawCardField($dd, 'Eff_Status_Change_Date', 2); ?>
			 <?php $this->drawCardField($dd, 'Future_Party', 2); ?>
			 <?php $this->drawCardField($dd, 'Future_Other_Party', 2); ?>
			 <?php $this->drawCardField($dd, 'Future_Party_Effective_Date', 2); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>
	  
	  
	  <div class="row mt-3">
		 <?php $this->drawCardGroupOpen('Address', 12); ?>
			 <?php $this->drawCardField($dd, 'House_Number', 1); ?>
			 <?php $this->drawCardField($dd, 'House_Number_Suffix', 2); ?>
			 <?php $this->drawCardField($dd, 'Street_Name', 3); ?>
			 <?php $this->drawCardField($dd, 'Apartment_Number', 1); ?>
			 <?php $this->drawCardField($dd, 'City', 2); ?>
			 <?php $this->drawCardField($dd, 'Zip_Code', 1); ?>
			 <?php $this->drawCardField($dd, 'Zip_Code4', 2); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>


	  <div class="row mt-3">
		 <?php $this->drawCardGroupOpen('District', 12); ?>
			 <?php $this->drawCardField($dd, 'Election_District'); ?>
			 <?php $this->drawCardField($dd, 'Assembly_District'); ?>
			 <?php $this->drawCardField($dd, 'Congress_District'); ?>
			 <?php $this->drawCardField($dd, 'Council_District'); ?>
			 <?php $this->drawCardField($dd, 'Senate_District'); ?>
			 <?php $this->drawCardField($dd, 'Civil_Court_District'); ?>
			 <?php $this->drawCardField($dd, 'Judicial_District'); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>
	  
	  
	  <div class="row mt-3">
		 <?php $this->drawCardGroupOpen('Demographics', 3); ?>
			 <?php $this->drawCardField($dd, 'Birth_Date'); ?>
			 <?php $this->drawCardField($dd, 'Gender'); ?>
		 <?php $this->drawCardGroupClose(); ?>
		 
		 <?php $this->drawCardGroupOpen('Mailing Address', 9); ?>
			 <?php $this->drawCardField($dd, 'Mailing_Address_1', 'md-auto'); ?>
			 <?php if ($dd['Mailing_Address_2']) 
					$this->drawCardField($dd, 'Mailing_Address_2', 'md-auto'); ?>
			 <?php if ($dd['Mailing_Address_3']) 
					$this->drawCardField($dd, 'Mailing_Address_3', 'md-auto'); ?>
			 <?php if ($dd['Mailing_Address_4']) 
					$this->drawCardField($dd, 'Mailing_Address_4', 'md-auto'); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>
	  
	  
	  <div class="row mt-3 mb-5">
		 <?php $this->drawCardGroupOpen('Voting History', 5); ?>
			 <?php $this->drawCardField($dd, 'Year_Last_Voted'); ?>
			 <?php $this->drawCardField($dd, 'Times_Voted'); ?>
		 <?php $this->drawCardGroupClose(); ?>
		 
		 <?php $this->drawCardGroupOpen('Registration', 7); ?>
			 <?php $this->drawCardField($dd, 'Registration_Date'); ?>
			 <?php $this->drawCardField($dd, 'Status_Code'); ?>
			 <?php $this->drawCardField($dd, 'Voter_Type'); ?>
		 <?php $this->drawCardGroupClose(); ?>
	  </div>
	  
	</div>
<?php			
}

public function drawCardGroupOpen($header, $width)
{
?>		 <div class="col-<?php echo $width; ?>">
			<div class="card">
				<div class="card-header"><?php echo "<u>{$header}</u>"; ?></div>
				<div class="card-body row">
<?php
}


public function drawCardGroupClose()
{
?>				</div>
		    </div>
		 </div>
<?php
}


public function drawCardField($dd, $f, $width=null)
{
	$label = $dd[$f];
	$label = $label == '' || $label == '0000-00-00' ? '-' : $label;
	echo $width 
		? "<div class=\"col-{$width}\">"
		: "<div class=\"col\">"
	?><small class="text-muted"><?php echo RequestMapper::getLabel($f); ?></small><br /><h6><?php echo $label; ?></h6></div><?php
}


public function drawNavbarBack()
{
?>	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="nav-link" onclick="window.history.back();" href="#">&laquo; Back to search results</a>
		  <div class="collapse navbar-collapse"></div>
		  <a class="nav-link my-2 my-lg-0" href="logout.php">Sign Out</a>
		</nav>
	</div>
<?php
}


public function drawVoterHistory($data)
{
	if (!$data)
		return;
	
	$hh = DataMapper::historyHeaders($data);
?>	<div class="container mt-4">
	  <table class="table">
		<thead>
		  <tr>
			<?php foreach ($hh as $i=>$h) : ?>
			  <th scope="col"><?php echo $h;?></th>
			<?php endforeach; ?>  
		  </tr>
		</thead>
		<tbody>
		  <?php foreach (DataMapper::historyData($data) as $row) : ?>
		    <tr>
			  <?php foreach ($row as $k=>$cell) : ?>
				<?php if ($k == '#') : ?>
				  <th><?php echo $cell; ?></th>
				<?php else : ?>
				  <td><?php echo $cell; ?></td>
				<?php endif; ?>
			  <?php endforeach; ?>
		    </tr>
		  <?php endforeach; ?>
		</tbody>
	  </table>
	</div>
<?php	
}


// --------- srv -------------------------------

// --- form groups -------------
public function drawFormGroup($id, $label, $value=null, $width=0)
{
	$width = $width ? "-{$width}" : '';
	echo "		<div class=\"col{$width}\">"; ?>
				<div class="form-group">
					<?php echo "<label for=\"{$id}\">";?><?php echo $label; ?></label>
					<?php echo "<input type=\"text\" class=\"form-control\" id=\"{$id}\" name=\"{$id}\"" . ($value ? " value=\"{$value}\">" : '>'); ?>
				</div>
			</div>
<?php	
}

public function drawFormGroupFromReq($field='', $req=[], $width=0)
{
	$this->drawFormGroup($field, RequestMapper::getLabel($field), $req[$field] ?? '', $width);
}

public function drawFormGroupMultiselect($field, $req=[], $width=6)
{
	//$field = 'Political_Party';
	$label = RequestMapper::getLabel($field);
	$values = $req[$field] ?? [];
	$parties = $field == 'Political_Party' 
					? Model::getParties()
					: Model::getFutureParties();
	
		echo "<div class=\"col-{$width}\">"; ?>
				<div class="form-group">
					<?php echo "<label for=\"{$field}\">{$label}</label>"; ?>
					<?php echo "<select multiple class=\"form-control\" id=\"{$field}[]\" name=\"{$field}[]\">"; ?>
						<?php foreach ($parties as $party) : ?>
						  <?php echo "<option value=\"{$party}\"" . (array_search($party, $values) !== false ? ' selected>' : '>'); ?>
						     <?php echo $party; ?>
						  </option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
<?php	
}

public function drawFormGroupLimits($id, $req=[], $width=6, $submit=false)
{
	$label = RequestMapper::getLabel($id);
	$min = $req[$id . '_min'] ?? null;
	$max = $req[$id . '_max'] ?? null;
	
	echo "		<div class=\"col-{$width}\">"; ?>
				<div class="form-group">
				  <div class="row">
					<div class="col-12">
						<?php echo "<label for=\"{$id}_min\">"; ?>
						  <?php echo $label; ?>
						</label>
					</div>
				  </div>
				  <div class="row">
					<div class="col-6">
					  <?php echo "<input type=\"text\" class=\"form-control\" id=\"{$id}_min\" name=\"{$id}_min\" placeholder=\"min\"" 
					        . (isset($min) ? " value=\"{$min}\">" : '>'); 
					  ?>
					</div>
					<div class="col-6 ">
					  <?php echo "<input type=\"text\" class=\"form-control\" id=\"{$id}_max\" name=\"{$id}_max\" placeholder=\"max\"" 
					        . (isset($max) ? " value=\"{$max}\">" : '>'); 
					  ?>
					</div>	
				  </div>
<?php if ($submit) $this->drawFormGroupSubmit(); ?>
				</div>
			</div>
<?php	
}

public function drawFormGroupSubmit()
{
?>		  <div class="d-flex flex-row-reverse mt-4">
			<div class="pl-4">
				<button style="min-width:8em;"  type="reset" class="btn btn-light" onclick="document.location.assign('search.php');">Reset</button>
			</div>
			<div class="pl-4">
				<button style="min-width:8em;" type="submit" class="btn btn-primary">Submit</button>
			</div>
		  </div>
<?php	
}


// --- /form groups -------------


public function drawNavbar($req=[], $csvAlert=false)
{
	$csvAlertStr = $csvAlert ? ' onclick="alert(\'Data dump is quite huge. For better convenience only first 100K rows will be exported.\')"' : '';
?>	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<?php if ($req) : ?>
			  <?php echo '<a class="nav-link" href="search.php?=' . RequestMapper::getEnc($req, [], true) . '">'; ?>&laquo; Back to search form</a>
			<?php endif; ?>
			<?php if ($req) : ?>
				<a class="nav-link"><?php echo RequestMapper::titleEnc($req, true); ?></a>
			<?php endif; ?>	
			<div class="collapse navbar-collapse"></div>
			<?php if ($req) : ?>
			  <?php echo '<a class="nav-link my-2 my-lg-0" href="csv.php?' . RequestMapper::getEnc($req, [], true) . '"' . $csvAlertStr . '>'; ?> CSV</a>
			<?php endif; ?>	
			<a class="nav-link my-2 my-lg-0" href="logout.php">Sign Out</a>
		</nav>
	</div>
<?php
}

public function drawHeaders()
{
?><!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title></title>
  </head>
  <body>
<?php
}

public function drawDataActualDateNotification()
{
?>  	<div class="container">
		  <div class="row mt-4 mb-4 justify-content-center">
			<div class="col-11 pt-2 text-muted" style="border-top: 1px solid #ccc; text-align:center;">
				<?php echo 'Data last updated <i>' . DATA_ACTUAL_DATE . '</i>'; ?>
			</div>	
		  </div>	
		</div>	
<?php	
}

public function drawFooters()
{
?>    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html><?php
}

}