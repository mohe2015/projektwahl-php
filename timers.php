<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
class Timers
{
    private $timers = [];

    public function startTimer($name, $description = null)
    {
        $this->timers[$name] = [
            'start' => microtime(true),
            'desc' => $description,
        ];
    }

    public function endTimer($name)
    {
        $this->timers[$name]['end'] = microtime(true);
    }

    public function getTimers()
    {
        $metrics = [];

        if (count($this->timers)) {
            foreach($this->timers as $name => $timer) {
                $timeTaken = ($timer['end'] - $timer['start']) * 1000;
                $output = sprintf('%s;dur=%f', $name, $timeTaken);

                if ($timer['desc'] != null) {
                    $output .= sprintf(';desc="%s"', addslashes($timer['desc']));
                }
                $metrics[] = $output;
            }
        }

        return implode($metrics, ', ');
    }
}
?>
