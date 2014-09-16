    <div id="control">
        <div id="accordion2" class="accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#fileAccordionTab">
                        Select GCode file
                    </a>
                </div>
                <div id="fileAccordionTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <input type="file" id="file" name="files" />
                        <!--<form class="add-teacher" id="fAddTeacher" enctype="multipart/form-data" method="post" novalidate="novalidate">-->
                        <!--<button id="selectFileButton">Select GCode File</button>-->
                        <!--</form>-->
                        <div id="drop_zone">Drop file here</div>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#progressAccordionTab">
                        Progress indicators
                    </a>
                </div>
                <div id="progressAccordionTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <div id="progressBlock">
                            <div class="progress" >
                                <div id="loadProgress" class="bar" style="width: 0;"></div>
                            </div>
                            <div class="progress" >
                                <div id="analyzeProgress" class="bar" style="width: 0;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#infoAccordionTab">
                        Model info
                    </a>
                </div>
                <div id="infoAccordionTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <p id="list"></p>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#layerAccordionTab">
                        Layer Info
                    </a>
                </div>
                <div id="layerAccordionTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <p id="layerInfo"></p>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#options2DAccordionTab">
                        2D Render options
                    </a>
                </div>
                <div id="options2DAccordionTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <label>Speed display type: </label>
                            <label for="speedDisplayRadio"><input type="radio" name="speedDisplay" id="speedDisplayRadio" value="1"  onclick="GCODE.ui.processOptions()" checked> mm/sec</label>
                            <label for="exPerMMRadio"><input type="radio" name="speedDisplay" id="exPerMMRadio" value="1"  onclick="GCODE.ui.processOptions()" > mm extrusion per mm move</label>
                            <label for="volPerSecRadio"><input type="radio"  name="speedDisplay" id="volPerSecRadio" value="1"  onclick="GCODE.ui.processOptions()" > mm^3/sec</label>
                        <label for="showMovesCheckbox"><input type="checkbox" id="showMovesCheckbox" value="1"  onclick="GCODE.ui.processOptions()" checked> Show non-extrusion moves</label>
                        <label for="showRetractsCheckbox"><input type="checkbox" id="showRetractsCheckbox" value="2" onclick="GCODE.ui.processOptions()" checked> Show retracts and restarts</label>
                        <label for="moveModelCheckbox"><input type="checkbox" id="moveModelCheckbox" value="3"  onclick="GCODE.ui.processOptions()" checked> Move model to the center of the grid</label>
                        <label for="differentiateColorsCheckbox"><input type="checkbox" id="differentiateColorsCheckbox" value="7"  onclick="GCODE.ui.processOptions()" checked> Show different speeds with different colors</label>
                        <label for="thickExtrusionCheckbox"><input type="checkbox" id="thickExtrusionCheckbox" value="8"  onclick="GCODE.ui.processOptions()"> Emulate extrusion width</label>
                        <label for="alphaCheckbox"><input type="checkbox" id="alphaCheckbox" value="10"  onclick="GCODE.ui.processOptions()" > Render lines slightly transparent</label>
                        <!--<label for="widthModifier">Width modifier: <input type="text" value="2" id="widthModifier" onchange="GCODE.ui.processOptions()"/></label>-->
                        <label for="showNextLayer"><input type="checkbox" id="showNextLayer" value="9"  onclick="GCODE.ui.processOptions()" > Show +1 layer</label>
                    </div>
                </div>
            </div>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#analyzeOptionsAccordioinTab">
                        GCode analyzer options
                    </a>
                </div>
                <div id="analyzeOptionsAccordioinTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        These require re-analyzing file:<br />
                        <label for="sortLayersCheckbox"><input type="checkbox" id="sortLayersCheckbox" value="4" onclick="GCODE.ui.processOptions()" checked>Sort layers by Z</label>
                        <label for="purgeEmptyLayersCheckbox"><input type="checkbox" id="purgeEmptyLayersCheckbox" value="5"  onclick="GCODE.ui.processOptions()" checked>Hide empty layers</label>
                        <label for="showGCodeCheckbox"><input type="checkbox" id="showGCodeCheckbox" value="6" onclick="GCODE.ui.processOptions()" checked>Show GCode in GCode tab (memory intensive!)</label>
                    </div>
                </div>
            </div>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#printerInfoAccordioinTab">
                        Printer Info
                    </a>
                </div>
                <div id="printerInfoAccordioinTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <label for="filamentDia">Plastic diameter: <input type="text" value="1.75" id="filamentDia" onchange="GCODE.ui.processOptions()"/></label>
                        <label>Plastic type: </label>
                            <label for="plasticABS"><input type="radio" name="radPlastic" id="plasticABS" value="ABS"  onclick="GCODE.ui.processOptions()" checked>ABS</label>
                            <label for="plasticPLA"><input type="radio" name="radPlastic" id="plasticPLA" value="PLA"  onclick="GCODE.ui.processOptions()" >PLA</label>
                        <label for="nozzleDia">Nozzle size: <input type="text" value="0.4" id="nozzleDia" onchange="GCODE.ui.processOptions()"/></label>
                    </div>
                </div>
            </div>

            <div class="accordion-group hide" id="errAnalyseTab">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#errorAnalysisAccordioinTab">
                        Error Analysis
                    </a>
                </div>
                <div id="errorAnalysisAccordioinTab" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <button class="btn disabled" id="runAnalysisButton" onclick="GCODE.analyzer.runAnalyze()"/>Run analysis</button>
                        <div id="analysisOptionsDiv" class="hide">
                            <label for="renderErrors"><input type="checkbox" id="renderErrors" onclick="GCODE.ui.processOptions()">Render error analysis results</label>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <output id="errorList"></output>


    </div>
    <div id="gcode">
        <div id="tabs-min" class="tabbable">
            <ul id="myTab" class="nav nav-tabs">
                <li class=""><a href="#tab2d" data-toggle="tab">2D</a></li>
                <li class=""><a href="#tab3d" data-toggle="tab">3D</a></li>
                <li class=""><a href="#tabGCode" data-toggle="tab">GCode</a></li>
                <li class="active"><a href="#tabAbout" data-toggle="tab">About</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="tab2d">
                    <canvas id="canvas" width="650" height="620"></canvas>
                    <div id="slider-vertical"></div>
                    <div id="slider-horizontal"></div>
                </div>
                <div class="tab-pane" id="tab3d">
                    <div id="3d_container"></div>
                </div>
                <div class="tab-pane" id="tabGCode">
                    <div id="gCodeContainer"></div>
                </div>
                <div class="tab-pane active" id="tabAbout">
                    <div class="aboutpage">
                        <h1>gCodeViewer</h1>
                        <b>gCodeViewer</b> is a visual GCode visualizer, viewer and analyzer in your own browser! It works on any OS in almost any modern browser (chrome, ff, safari 6, opera, ie10 should work too). All you need to do - is drag your *.gcode file to the designated zone.<br /><br />
                        Current features include:<br />
                        <ul>
                            <li>Visualize GCode in 2D, layer by layer
                                <ul>
                                    <li>Show retracts and restarts</li>
                                    <li>Show print/move/retract speeds</li>
                                    <li>Display only part of layer, animate sequence of layer printing</li>
                                    <li>Show two layers simultaneously so you can check overhangs</li>
                                    <li>Adjust line width to simulate print more closely</li>
                                    <li><b>Gcode viewer will try to parse nozzle and filament diameters from gcode, but it may fail to do so. In that case you will need to set it manually in 'Printer Info' tab</b></li>
                                </ul>
                            </li>
                            <li>Analyze GCode
                                <ul>
                                    <li>Print time, amount of plastic used, layer height, etc. for whole file and for single layer</li>
                                    <li>Reference visualized part to GCode file (i.e. select a certain part of 2d visualization, switch to GCode view - it will highlight list of lines responsible for visualized piece)</li>
                                </ul>
                            </li>
                            <li>Multiplatform, available online (but works offline too), works locally (doesn't upload you g-code anywhere or download anything but application itself)</li>
                            <li>3D visualization (I don't know what for, it's dull and useless.. need to rewrite it from scratch)</li>
                            <li>And yeah, it's slow, memory hungry and only supports modern browsers</li>
                            <li>Totally open source so you can use it as you like</li>
                        </ul>
                        <br /><br />
                        All sources are available at <a href="https://github.com/hudbrog/gCodeViewer">https://github.com/hudbrog/gCodeViewer</a><br />
                        If you find any bugs or have feature requests - don't hesitate to post them to <a href="https://github.com/hudbrog/gCodeViewer/issues">https://github.com/hudbrog/gCodeViewer/issues</a><br />
                        And I would appreciate if you like it on Thingiverse page: <a href="http://www.thingiverse.com/thing:35248">http://www.thingiverse.com/thing:35248</a><br />
                    </div>

                </div>
            </div>
        </div>
    </div>

