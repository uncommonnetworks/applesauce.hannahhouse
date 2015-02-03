<?php

use Carbon\Carbon;


class ReportsController extends BaseController
{

    public static function getIntake()
    {
        Excel::load( storage_path('reports') . '/intake.xlsx', function($reader)
        {
            $reader->sheet(function($sheet){

                $resident = Resident::findOrFail(Input::get('resident_id'));

                $sheet->cell('B6', function($cell) use($resident){
                        $cell->setValue($resident->last_name);
                });

                $sheet->cell('J6', function($cell) use($resident){
                    $cell->setValue($resident->given_names);
                });

                $sheet->cell('T6', function($cell) use($resident){
                    $cell->setValue($resident->goes_by_name);
                });

                $sheet->cell('D9', function($cell) use($resident){
                    $cell->setValue($resident->residency->start_date->format(Config::get('format.date')));
                });

                $sheet->cell('M9', function($cell) use($resident){
                    $cell->setValue($resident->date_of_birth_dmy);
                });

                $sheet->cell('T9', function($cell) use($resident){
                    $cell->setValue($resident->marital_status);
                });

                // has this person stayed here previously?
                if( count($resident->residencies) > 1 )
                {
                    $sheet->cell('I12', function($cell){
                        $cell->setValue('X');
                    });


                    $sheet->cell('T12', function($cell) use($resident){
                        $cell->setValue($resident->most_recent_residency->start_date->format(Config::get('format.date')));
                    });

                }
                else
                {
                    $sheet->cell('K12', function($cell){
                        $cell->setValue('X');
                    });
                }

                $sheet->cell('B15', function($cell) use($resident){
                    $cell->setValue($resident->contact_name);
                });

                $sheet->cell('J15', function($cell) use($resident){
                    $cell->setValue($resident->contact_relationship);
                });

                $sheet->cell('R15', function($cell) use($resident){
                    $cell->setValue($resident->contact_phone);
                });

                if($resident->sin)
                {
                    $sheet->cell('B22', function($cell) use($resident){
                        $cell->setValue($resident->sin);
                    });

                    if($resident->idSin)
                    {
                        $sheet->cell('K22', function ($cell) {
                            $cell->setValue('X');
                        });
                    }
                }

                if($resident->health_card_number)
                {
                    $sheet->cell('B19', function($cell) use($resident){
                        $cell->setValue($resident->health_card_number);
                    });

                    if($resident->idHealth)
                    {
                        $sheet->cell('K19', function ($cell) {
                            $cell->setValue('X');
                        });
                    }
                }

                // dietary concerns
//                if(count($resident->allergies))
                {
                    $sheet->cell('O25', function($cell) use($resident){
                        $cell->setValue($resident->dietary_concerns);
                    });
                }


                $sheet->cell('H37', function($cell) use($resident){
                    $cell->setValue(substr($resident->last_name, 0, 5));
                });

                $genderCell = $resident->gender == 'M' ? 'S37' : 'U37';

                $sheet->cell($genderCell, function($cell){
                    $cell->setValue('X');
                });


                $sheet->cell('E39', function($cell) use($resident){
                    $cell->setValue($resident->dob->format('d'));
                });

                $sheet->cell('E41', function($cell) use($resident){
                    $cell->setValue($resident->dob->format('m'));
                });

                $sheet->cell('E43', function($cell) use($resident){
                    $cell->setValue($resident->dob->format('Y'));
                });

                $sheet->cell('E45', function($cell) use($resident){
                    $cell->setValue($resident->residency->start_date->format('Y'));
                });


                $sheet->cell('R55', function($cell) use($resident){
                    $cell->setValue($resident->intakes_this_month);
                });

                // here before beginning of month?
                $lastMonthBegin = new Carbon('first day of last month');
                $lastMonthEnd = new Carbon('first day of this month');
                if( $resident->most_recent_residency->start_date->gte($lastMonthBegin) &&
                    $resident->most_recent_residency->start_date->lt($lastMonthEnd))
                    $markCell = 'Q57';
                else
                    $markCell = 'S57';

                $sheet->cell($markCell, function($cell){
                    $cell->setValue('X');
                });


                // meals list updated
                $sheet->cell('G77', function($cell){
                    $cell->setValue('X');
                });


            });

        })->download('xls');
    }


    public static function getWhite()
    {
        Excel::load( storage_path('reports') . '/white.xls', function($reader)
        {
            $reader->sheet(function($sheet){

                $resident = Resident::findOrFail(Input::get('resident_id'));

                $sheet->cell('E6', function($cell) use($resident)
                {
                    $cell->setValue($resident->full_name);
                });

                $sheet->cell('R35', function($cell) use($resident){
                    $cell->setValue($resident->residency->start_date->format('M d, Y'));
                });
            });

        })->download('xls');
    }


    public static function getOuttake()
    {
        Excel::load(storage_path('reports') . '/outtake.xls', function($reader){

            $reader->sheet(function($sheet){

                $resident = Resident::findOrFail(Input::get('resident_id'));

                $sheet->cell('B6', function($cell) use($resident){
                    $cell->setValue($resident->last_name);
                });

                $sheet->cell('J6', function($cell) use($resident){
                    $cell->setValue($resident->first_name);
                });

                $sheet->cell('T6', function($cell) use($resident){
                    $cell->setValue($resident->most_recent_residency->end_date->format(Config::get('format.date')));
                });

                if($resident->current_address)
                {
                    $street = $resident->current_address->street1;
                    if($resident->current_address->street2)
                        $street .= "; {$resident->current_address->street2}";

                    if(isset($street))
                        $sheet->cell('B10', function($cell) use($street){
                            $cell->setValue($street);
                        });


                    $city = $resident->current_address->city;

                    if(!$city)
                        $city = $resident->current_address->region;

                    if($city)
                        $sheet->cell('J10', function($cell) use($city){
                            $cell->setValue($city);
                        });

                    if($postal = $resident->current_address->postal)
                        $sheet->cell('R10', function($cell) use($postal){
                            $cell->setValue($postal);
                        });
                }

                $sheet->cell('S17', function($cell) use($resident){
                    $cell->setValue($resident->intakes_this_month);
                });

                $sheet->cell('S19', function($cell) use($resident){
                    $cell->setValue(BedHistory::residentSince($resident, new Carbon('first day of this month'))->count());
                });

            });
        })->download('xls');
    }


    public static function getMealsChecklist()
    {
        Excel::load( storage_path('reports') . '/meals-checklist.xls', function($reader)
        {

           $reader->sheet(function($sheet){

               // todays' date
               $today = Carbon::now();
               $tenpm = new Carbon('10pm');
               if( $tenpm->gt($today) )
                   $today->addDay();



               $sheet->cell('B3', function($cell) use($today){
                   $cell->setValue("Meals Checklist - " . $today->format(Config::get('format.date')));
               });


               $row = 5;

               foreach( Resident::current()->get() as $resident )
               {
                    $sheet->cell("B{$row}", function($cell) use($resident){
                        $cell->setValue($resident->display_name);
                    });

                   $row++;
               }


           });

        })->download('xls');
    }



    public static function getSignInSheet()
    {
        Excel::load( storage_path('reports') . '/sign-in-sheet.xls', function($reader)
        {

            $reader->sheet(function($sheet){

                // todays' date
                $today = Carbon::now();
                $tenpm = new Carbon('10pm');
                if( $tenpm->gt($today) )
                    $today->addDay();



                $sheet->cell('B2', function($cell) use($today){
                    $cell->setValue("Sign In Sheet - " . $today->format(Config::get('format.date')));
                });


                $row = 4;

                foreach( Resident::current()->get() as $resident )
                {
                    $sheet->cell("B{$row}", function($cell) use($resident){
                        $cell->setValue($resident->display_name);
                    });

                    $row++;
                }


            });

        })->download('xls');
    }

    public static function getOwWeekly()
    {
        return View::make('reports.ow-weekly');
    }

    public static function postOwWeekly()
    {
        $untilDate = new Carbon(Input::get('untilDate'));
        $fromDate = new Carbon(Input::get('untilDate'));
        $fromDate = $fromDate->subDays(7);

        Excel::load( storage_path('reports') . '/ow-weekly.xls', function($reader) use($fromDate,$untilDate)
        {

            $reader->sheet(function($sheet) use($fromDate,$untilDate){

                $row = 2;

                foreach( Residency::notMoved($fromDate, $untilDate)->get() as $current )
                {
                    $sheet->prependRow($row++, ['', $current->resident->sin ? $current->resident->sin : $current->resident->date_of_birth_dmy,
                        $current->resident->display_name,
                        $current->start_date->format('M d')
                    ]);
                }


                $row += 3;

                foreach( Residency::movedOut($fromDate, $untilDate)->get() as $movedOut )
                    $sheet->prependRow($row++, ['', $movedOut->resident->sin ? $movedOut->resident->sin : $movedOut->resident->date_of_birth_dmy,
                        $movedOut->resident->display_name,
                        $movedOut->start_date->format('M d'),
                        $movedOut->end_date->format('M d')
                    ]);

                $row += 3;

                foreach( Residency::movedIn($fromDate, $untilDate)->get() as $movedIn ){
                    Log::info($movedIn->resident);
                    $sheet->prependRow($row++, ['', $movedIn->resident->sin ? $movedIn->resident->sin : $movedIn->resident->date_of_birth_dmy,
                        $movedIn->resident->display_name,
                        $movedIn->start_date->format('M d')
//                        $movedIn->end_date ? $movedIn->end_date->format('M d') : '']);
                    ]);
                }

            });

        })->download('xls');
    }

    public static function getShelterReport()
    {
        return View::make('reports.shelter-report');
    }

    public static function postShelterReport()
    {
        $year = Input::get('year');

        Excel::load( storage_path('reports') . '/shelter-report.xls', function($reader) use($year){
            $reader->sheet(function($sheet) use($year){

                $sheet->cell('B3', function($cell) use($year){
                    $cell->setValue("Year - {$year}");
                });

                $row = 8;
                foreach( Resident::inYear($year) as $resident ) {
                    $data = $resident->stats($year);

                    $sheet->prependRow($row++, array_merge(['',
                            $resident->full_name, '',
                            $data['numberOfStays'],
                            $data['totalBedNights'],
                            "=E{$row}/D{$row}"],
                            $data['byMonth'])
                    );
                }



            });
        })->download('xls');
    }

    public static function getBeddingMap()
    {
        Excel::load( storage_path('reports') . '/bedding-map.xls', function($reader) {


           $reader->sheet('SmallDorms', function($sheet){
//                $sheet->row(3, [ 'Fraser, Arran', '', 'current']);
//               $sheet->row(6, [ 'Fraser, Arran', '', 'current']);
               $sheet->cell('A4', 'Fraser, Arran');
               $sheet->cell('F1', Carbon::now()->format( 'M d, Y'));
/*
               foreach( Bed::inRoom('MenDormS') as $bed )
                   if( $bed->resident && $bed->mapPosition )
//                       Log::info("{$bed->mapPosition}: {$bed->resident->display_name}");
                        $sheet->cell( $bed->mapPosition, function($cell){ $cell->setValue($bed->resident->display_name);}  );
*/
           });
        })->download('xls');
    }
}
