<?php
//ystockquote.php

class ystockquote {
	public function __construct($ticker) {
		$this->ticker = $ticker ;
		}
		
	public function request($stat) {
		// request() uses the csv yahoo finance api to grab the specific stats for a specific ticker
		$url = "http://finance.yahoo.com/d/quotes.csv?s={$this->ticker}&f={$stat}&e=.csv";
		
		if ($handle = fopen($url,"r") !== FALSE){	
			$handle = fopen($url,"r");
			return fgetcsv($handle);
			}
		}
		
	public function get_all() {
		$stat = "l1o0p0h0g0c1m7m5k4j5r1d0y0d1a2m3m4x0s0c4s6t8kjvj1b4e0j4e7e9e8r5r0s7p6r6r7";
		$line = $this->request($stat);
		$data = array();
		
		// pricing
		$data["price"] = $line[0]; //l1
		$data["open"] = $line[1]; //o0
		$data["previous_close"] = $line[2]; //p0
		$data["high"] = $line[3]; //h0
		$data["low"] = $line[4]; //g0
		
		// changes
		$data["change"] = $line[5]; //c1
		$data["change_from_50day"] = $line[6]; //m7
		$data["change_from_200day"] = $line[7]; //m5
		$data["change_from_year_high"] = $line[8];  //k4
		$data["change_from_year_low"] = $line[9];  //j5
		//$data["change_in_percent"] = $line[]; //c0->currently not working?
		
		// dividends
		$data["dividend_pay_date"] = $line[10]; //r1
		$data["annual_div_yield"] = $line[11]; //d0
		$data["annual_div_yield_percent"] = $line[12]; //y0
		
		// date
		$data["last_trade_date"] = $line[13]; //d1
		
		// averages
		$data["avg_daily_volume"] = $line[14]; //a2
		$data["50_day_moving"] = $line[15]; //m3
		$data["200day_moving_avg"] = $line[16]; //m4	
		
		// misc
		$data["exchange"] = $line[17]; //x0
		$data["symbol"] = $line[18]; //s0
		$data["currency"] = $line[19]; //c4
		$data["revenue"] = $line[20]; //s6
		$data["one_year_target"] = $line[21]; //t8
		
		// 52 week pricing
		$data["52_week_high"] = $line[22]; //k
		$data["52_week_low"] = $line[23]; //j
		
		// volume
		$data["volume"] = $line[24]; //v
		$data["market_cap"] = $line[25]; //j1
		
		
		// ratios
		$data["book_val_per_share"] = $line[26]; //b4	
		$data["diluted_eps"] = $line[27]; //e0
		$data["ebitda"] = $line[28]; //j4
		$data["eps_est_current_year"] = $line[29]; //e7
		$data["eps_est_quart"] = $line[30]; //e9
		$data["eps_est_next_year"] = $line[31]; //e8
		$data["PEG_ratio"] = $line[32]; //r5
		$data["PE_ratio"] = $line[33]; //r0
		$data["short_ratio"] = $line[34]; //s7
		$data["book_price"] = $line[35]; //p6
		$data["Price_EPS_est_curr_year"] = $line[36]; //r6
		$data["Price_EPS_est_next_year"] = $line[37]; //r7	
		
		return $data;
		}
		
	public function get_afterHoursChangeRealtime() {
		$stat = 'c8';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_annualizedGain() {
		$stat = 'g3';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_ask() {
		$stat = 'a0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_askRealtime() {
		$stat = 'b2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_askSize() {
		$stat = 'a5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_avgDailyVol() {
		$stat = 'a2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bid() {
		$stat = 'b0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bidRealtime() {
		$stat = 'b3';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bidSize() {
		$stat = 'b6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_bookValuePerShare() {
		$stat = 'b4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_change() {
		$stat = 'c1';
		$line = $this->request($stat);
		
		return $line[0];
		}

	public function get_changeFrom50DayMovingAvg() {
		$stat = 'm7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeFrom200DayMovingAvg() {
		$stat = 'm5';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_changeFromYearHigh() {
		$stat = 'k4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_changeFromYearLow() {
		$stat = 'j5';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_changeInPercent() {
		$stat = 'p2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeInPercentRealtime() {
		$stat = 'k2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeRealtime() {
		$stat = 'c6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_commision() {
		$stat = 'c3';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_currency() {
		$stat = 'c4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayHigh() {
		$stat = 'h0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_dayLow() {
		$stat = 'g0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_dayRange() {
		$stat = 'm0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayRangeRealtime() {
		$stat = 'm2';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_dayValueChange() {
		$stat = 'w1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayValueChangeRealtime() {
		$stat = 'w4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dividendPayDate() {
		$stat = 'r1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_trailingAnnualDividendYield() {
		$stat = 'd0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_trailingAnnualDividendYieldInPercent() {
		$stat = 'y0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dilutedEPS() {
		$stat = 'e0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_ebitda() {
		$stat = 'j4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_EPSEstimateCurrentYear() {
		$stat = 'e7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_EPSEstimatenextQuarter() {
		$stat = 'e9';
		$line = $this->request($stat);
		
		return $line[0];
		}	
	
	public function get_EPSEstimateNextYear() {
		$stat = 'e8';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_exDividendDate() {
		$stat = 'q0';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_50day_moving_average() {
		$stat = 'm3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesFloat() {
		$stat = 'f6';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_highLimit() {
		$stat = 'l2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsGain() {
		$stat = 'g4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsGainPercent() {
		$stat = 'g1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsGainPercentRealtime() {
		$stat = 'g5';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_holdingsGainRealtime() {
		$stat = 'g6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsValue() {
		$stat = 'v1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsValueRealtime() {
		$stat = 'v7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeDate() {
		$stat = 'd1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradePriceOnly() {
		$stat = 'l1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeRealtimeWithTime() {
		$stat = 'k1';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_lastTradeSize() {
		$stat = 'k3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeTime() {
		$stat = 't1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeWithTime() {
		$stat = 'l0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lowLimit() {
		$stat = 'l3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_marketCapitalization() {
		$stat = 'j1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_marketCapRealtime() {
		$stat = 'j3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_moreInfo() {
		$stat = 'i0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_name() {
		$stat = 'n0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_notes() {
		$stat = 'n4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_oneYearTargetPrice() {
		$stat = 't8';
		$line = $this->request($stat);
		
		return $line[0];
		}

	public function get_orderBookRealtime() {
		$stat = 'i5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PEGRatio() {
		$stat = 'r5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PERatio() {
		$stat = 'r0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PERatioRealtime() {
		$stat = 'r2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_percentChangeFrom50dayMovingAverage() {
		$stat = 'm8';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_percentChangeFrom200dayMovingAverage() {
		$stat = 'm6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeInPercentFromYearHigh() {
		$stat = 'k5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_percentChangeFromYearLow() {
		$stat = 'j6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_previousClose() {
		$stat = 'p0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_open() {
		$stat = 'o';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceBook() {
		$stat = 'p6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceEPSEstimateCurrentYear() {
		$stat = 'r6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceEPSEstimateNextYear() {
		$stat = 'r7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_pricePaid() {
		$stat = 'p1';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_priceSales() {
		$stat = 'p5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_revenue() {
		$stat = 's6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesOwned() {
		$stat = 's1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesOutstanding() {
		$stat = 'j2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_shortRatio() {
		$stat = 's7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_stockExchange() {
		$stat = 'x0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_symbol() {
		$stat = 's0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tickerTrend() {
		$stat = 't7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tradeDate() {
		$stat = 'd2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_tradeLinks() {
		$stat = 't6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tradeLinksAdditional() {
		$stat = 'f0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_200day_moving_average() {
		$stat = 'm4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_volume() {
		$stat = 'v';
		$line = $this->request($stat);
		
		return $line[0];
		}		
		
	public function get_52_week_high() {
		$stat = 'k';
		$line = $this->request($stat);
		
		return $line[0];
		} 
		
	
	public function get_52_week_low() {
		$stat = 'j';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	
	public function get_historical_prices($start_date,$end_date,$period) {
		/*
			Month and day must be decremented, while year is left the same
			Trading periods: 
				daily: d
				weekly: w
				monthly: m
				
			This returns an array of arrays; each array contains the following:
				Date, Open, High, Low, Close, Volume, Adjusted Close
		*/
		list($month_from, $day_from, $year_from) = explode('-',$start_date);
		list($month_to, $day_to, $year_to) = explode('-',$end_date);
		
		$month_from -= 1;
		$month_to -= 1;
		
		$url = "http://ichart.yahoo.com/table.csv?s={$this->ticker}&a={$month_from}&b={$day_from}&c={$year_from}&d={$month_to}&e={$day_to}&f={$year_to}&g={$period}&ignore=.csv";

		if ($file = fopen($url,"r")){
			$row = 0;
			$hist_data = array();
			while ($data = fgetcsv($file,200,",")) {
				if ($row != 0) {
					$daily = array();
					$daily["Date"] = $data[0];
					$daily["Open"] = $data[1];
					$daily["High"] = $data[2];
					$daily["Low"] = $data[3];
					$daily["Close"] = $data[4];
					$daily["Volume"] = $data[5];
					$daily["Adj Close"] = $data[6];
					
					array_push($hist_data, $daily);
					}
					
				$row++;
				}
			fclose($file);
			}
		else {
			//echo "Error opening the file for $this->ticker.<br />";
			$hist_data = '';
			}

		return $hist_data;	
		}
}

	/* Example of how to use: */
	//$FB = new ystockquote('FB'); // generates new ystockquote for FB
	//$all = $FB->get_all(); // gets all data for FB
	
	//foreach ($all as $key=> $value){
	//	echo "{$key}: {$value} <br/>";
	//	}
	/*
	$data = $FB->get_historical_prices('01/01/1800','10/14/2013','d'); // grabs daily historical prices for FB for the timeframe Jan 1, 2012 to Oct 10, 2013
	foreach ($data as $value) {
		print_r($value);
		echo "<br />";	
		}
	*/

?>