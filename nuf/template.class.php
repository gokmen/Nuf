<?php

    # Copyright (C) 2011-2012, Nuf (~Fun)
    # 2011 - Nuno Freitas <nunofreitas@gmail.com>
    # 2011,2012 - Gökmen Göksel <gokmen@goksel.me>
    #             http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    /**
     * Simple template engine class (use [@tag] tags in your templates).
     *
     * @link http://www.broculos.net/ Broculos.net Programming Tutorials
     * @author Nuno Freitas <nunofreitas@gmail.com>
     * @author Gökmen Göksel <gokmen@goksel.me>
     * @version 1.0
     */
    class Template {
        /**
         * The filename of the template to load.
         *
         * @access protected
         * @var string
         */
        protected $file;

        /**
         * An array of values for replacing each tag on the template (the key for each value is its corresponding tag).
         *
         * @access protected
         * @var array
         */
        protected $values = array();

        /**
         * Creates a new Template object and sets its associated file.
         *
         * @param string $file the filename of the template to load
         */
        public function __construct($file) {
            $this->file = $file;
        }

        /**
         * Sets a value for replacing a specific tag.
         *
         * @param string $key the name of the tag to replace
         * @param string $value the value to replace
         */
        public function set($key, $value) {
            $this->values[$key] = $value;
        }

        /**
         * Outputs the content of the template, replacing the keys for its respective values.
         *
         * @return string
         */
        public function output($keep_keywords = false, $ignore_warnings = false) {
            /**
             * Tries to verify if the file exists.
             * If it doesn't return with an error message.
             * Anything else loads the file contents and loops through the array replacing every key for its value.
             */
            if (!file_exists($this->file)) {
                if ($ignore_warnings)
                    return $this->file;
                else
                    return "Error loading template file ($this->file).<br />";
            }

            $output = file_get_contents($this->file);

            foreach ($this->values as $key => $value) {
                $tagToReplace = "[@$key]";
                $output = str_replace($tagToReplace, $value, $output);
            }

            if (!$keep_keywords)
                // Remove any unused template tag
                $output = preg_replace("/(\[@.+?\])/i", "", $output);

            return $output;
        }

        /**
         * Merges the content from an array of templates and separates it with $separator.
         *
         * @param array $templates an array of Template objects to merge
         * @param string $separator the string that is used between each Template object
         * @return string
         */
        static public function merge($templates, $separator = "\n") {
            /**
             * Loops through the array concatenating the outputs from each template, separating with $separator.
             * If a type different from Template is found we provide an error message. 
            */
            $output = "";

            foreach ($templates as $template) {
                $content = (get_class($template) !== "Template")
                    ? "Error, incorrect type - expected Template."
                    : $template->output();
                $output .= $content . $separator;
            }

            return $output;
        }
    }

?>
