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
var Dracula = require('graphdracula')

var Graph = Dracula.Graph
var Renderer = Dracula.Renderer.Raphael
var Layout = Dracula.Layout.Spring

var graph = new Graph()

graph.addEdge('Banana', 'Apple')
graph.addEdge('Apple', 'Kiwi')
graph.addEdge('Apple', 'Dragonfruit')
graph.addEdge('Dragonfruit', 'Banana')
graph.addEdge('Kiwi', 'Banana')

var layout = new Layout(graph)
var renderer = new Renderer('#paper', graph, 400, 300)
renderer.draw()
