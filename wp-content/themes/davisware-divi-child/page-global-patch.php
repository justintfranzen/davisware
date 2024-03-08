<?php
/*
Template Name: Global Patch Page
*/

require_once __DIR__ . '/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

get_header();

$post_id = get_the_ID();
?>


  <section class="et_pb_section" id="global-patch-port">
    <div class="et_pb_row et_pb_row_0" style="margin-top: 6em;">
      <div class="et_pb_column ">
        <script>
          function versionSelect(version) {
            if (version == 'all') {
              jQuery('.release-notes tbody tr').show();
            } else {
              jQuery('.release-notes tbody tr').hide();
              jQuery('.release-notes tbody tr.rel-' + version).show();
            }
          }

          jQuery(document).ready(function ($) {
            $('#version-select').on('change', function () {
              $('.release-notes-container').hide();
              $('.loader').show();
            });
          });

        </script>

        <?php
        /**
         * JIRA API code
         */

        // Check current page slug for project value

        global $post;
        $post_slug = $post->post_name;

        // Dynamic fetching of product not necessary
        $product_key = 'GEP';

        // Make JIRA API calls for versions

        $site_root = get_home_path();

        $client = new Client(['base_uri' => 'https://davisware.atlassian.net/rest/api/3/']);

        $get_versions = $client->request('GET', 'project/' . $product_key . '/versions', [
          'auth' => [
            'ptran@davisware.com',
            'ATATT3xFfGF05tPmNIrQS7KI0Ze6o20rMPfpXp9tQgFzQqZ-sMkxJCpNbuzoOpf32m-hMxwcFe5QRmIXX6yrUTyw1wLEjGl4aDqIPrRXUg3xYhaHvX7gF-IzRXRxol12eMaizzAFmwTUMGfBvPlT_RLuP52600IyyojDjZpOqehfSpFX-djCHGQ=820ABA7E',
          ],
        ]);

        // Include a link to return to Zendesk
        $back_link = 'categories/360004035994-GlobalEdge';

        echo '<p style="margin-bottom:20px;"><a href="https://daviswarehelp.zendesk.com/hc/en-us/' .
          $back_link .
          '"><i class="fa fa-long-arrow-left"></i> Back to Knowledge Base</a></p>';

        //
        // Build version select menu
        //

        $versions_raw = $get_versions->getBody()->getContents();
        $versions = json_decode($versions_raw, true);

        // Explicitly ignore certain items
        $ignore = ['GE Parking Lot ', 'Parking Lot', 'Priority Parking', 'Cancelled'];

        // Remove items that don't have a release date, versions before 22.x, etc.
        foreach ($versions as $subKey => $subArray) {
          if (
            in_array($subArray['name'], $ignore) ||
            !array_key_exists('releaseDate', $subArray) ||
            strtotime($subArray['releaseDate']) < 1641013200 ||
            strtotime($subArray['releaseDate']) > time() ||
            $subArray['archived'] == '1'
          ) {
            unset($versions[$subKey]);
          }
        }

        // Group versions by release number for sorting
        $versions_grouped = [];

        foreach ($versions as $subKey => $subArray) {
          $ver_comp = str_replace('.', '', explode('R', $subArray['name']));
          $versions_grouped[$ver_comp[0]][] = $subArray['name'];
        }

        foreach ($versions_grouped as $ver_g) {
          foreach ($ver_g as $key => $value) {
            if (strpos($value, 'REL') !== false) {
              $sort_alpha[$ver_g[0]][] = explode('REL', $value)[1];
            } else {
              $sort_alpha[$ver_g[0]][] = $value;
            }
          }
        }

        array_multisort(array_keys($versions_grouped), SORT_NATURAL, $versions_grouped);

        $versions_final = array_reverse(array_merge(...$versions_grouped));

        // Get version parameter from URL. If empty, use the latest version.
        if (isset($_GET['version'])) {
          $version_param = $_GET['version'];
        } else {
          $version_param = $versions_final[0];
        }

        echo '<div class="version-select-wrapper">';
        echo '<form id="version-form" action="/' . $post_slug . '" method="GET">';
        echo '<label for="version">Select a software version</label>';
        echo '<select required id="version-select" name="version" onchange="if(this.value != 0) { this.form.submit(); }">';

        foreach ($versions_final as $version) {
          echo '<option value="' . $version . '">' . $version . '</option>';
        }
        echo '</select>';
        echo '</form>';
        echo '</div>';
        ?>

        <?php
        // Display selected version in dropdown
        if (isset($version_param)) { ?>

          <script>
            selectElement('version-select', '<?php echo $version_param; ?>');

            function selectElement(id, valueToSelect) {
              let element = document.getElementById(id);
              element.value = valueToSelect;
            }
          </script>

        <?php }
        // Faux loader
        echo '<div class="loader"><img src="' . get_stylesheet_directory_uri() . '/assets/img/loader.gif" /></div>';

        // Build issues array by calling API correct # of times to get all available issues (based on 'total')

        $i = 0;
        $calls = 1;
        $start = 0;

        while ($i++ < $calls) {
          // Modified 06-01-23 - replaced Kim's credentials with ptran@davisware.com
          // Modified 08-14-23 - modified to include statuses other than "Done"
          $get_issues = $client->request(
            'GET',
            'search?jql=project=' .
              $product_key .
              '+AND+fixVersion="' .
              $version_param .
              '"+AND+type+IN(Bug, "Form Request", "Product Gap", Story)+AND+status+IN("Done", "Code Review", "QA Backlog", "QA Assigned", "QA In Progress", "QA Pending", "Product Testing")+AND+cf[10088]+IN+("Fixed code", "Fixed data", "Fixed stored procedure", "Fixed web service", "New development")&maxResults=100&startAt=' .
              $start .
              '&fields=summary,customfield_10046,customfield_10075,customfield_10088,customfield_10097,customfield_10098,resolution,issuetype,fixVersions',
            [
              'auth' => [
                'ptran@davisware.com',
                'ATATT3xFfGF05tPmNIrQS7KI0Ze6o20rMPfpXp9tQgFzQqZ-sMkxJCpNbuzoOpf32m-hMxwcFe5QRmIXX6yrUTyw1wLEjGl4aDqIPrRXUg3xYhaHvX7gF-IzRXRxol12eMaizzAFmwTUMGfBvPlT_RLuP52600IyyojDjZpOqehfSpFX-djCHGQ=820ABA7E',
              ],
              'http_errors' => false,
            ],
          );

          $issues_body = $get_issues->getBody()->getContents();
          $issues_output = json_decode($issues_body, true);

          if ($i == 1) {
            $total = $issues_output['total'];
            $calls = ceil($total / 100);
            $issues_arr = [];
          }
          $issues_arr = array_merge($issues_arr, $issues_output['issues']);

          $start = $start + 100;
        }

        // Build release notes table

        if (!empty($issues_arr)) {
          // Create array for Release Version numbers

          $rel_list = [];
          $total = 0;

          // Check version name for 'REL' to discern between old and new version formats
          $version_check = strpos($version_param, 'REL');

          foreach ($issues_arr as $patch) {
            $total++;

            // Build patch note from array of separate text items

            $note_arr = $patch['fields']['customfield_10046']['content'];

            $note_concat = null;

            foreach ($note_arr as $note_item) {
              foreach ($note_item['content'] as $n) {
                if ($n['type'] == 'hardBreak') {
                  $note_concat .= '';
                } elseif ($n['type'] == 'text') {
                  $note_concat .= '<p>' . $n['text'] . '</p>';
                }
              }
            }

            //TODO: Waiting on instruction on how to handle multiple FixVersions for an issue

            $rel_num = $patch['fields']['customfield_10097'];
            if ($version_check == false) {
              $rel_date = $patch['fields']['customfield_10098'];
            } else {
              $rel_date = $patch['fields']['fixVersions'][0]['releaseDate'];
            }

            $jira_id = $patch['key'];
            $zendesk_id = $patch['fields']['customfield_10075']['content'][0]['content'][0]['text'] ?? null;
            $patch_note = $note_concat;
            $parent_title = $patch['fields']['summary'];

            $patch_info = [
              'parent' => $parent_title,
              'note' => $patch_note,
              'zendesk-id' => $zendesk_id,
              'jira-id' => $jira_id,
            ];

            // Add unique release values to Release Version array for populating dropdown

            if (!in_array($rel_num, $rel_list)) {
              $rel_list[] = $rel_num;
            }

            // Sort releases naturally

            usort($rel_list, function ($a, $b) {
              return strnatcmp($b, $a);
            });

            // Build patch info array (different structure based on $version_check value)

            if ($version_check == false) {
              $fixes[$rel_date][$rel_num][] = $patch_info;
            } else {
              $fixes[$rel_date][] = $patch_info;
            }
          }

          // Display release notes if there are any

          if (!empty($fixes)) {
            echo '<div class="release-notes-container">';

            echo '<h2>Version - ' . $version_param . '</h2>';

            // Build External Release dropdown if Version name doesn't contain "REL"

            if ($version_check == false) {
              echo '<p><strong>Filter by release number</strong></p>';
              echo '<select id="release-select" onChange="versionSelect(this.options[this.selectedIndex].value)">';
              echo '<option value="all">All Releases</option>';

              foreach ($rel_list as $rel_option) {
                echo '<option value="' . $rel_option . '">' . $rel_option . '</option>';
              }

              echo '</select>';
            }

            // Build table

            echo '<table class="basic-table release-notes tablesaw tablesaw-swipe" data-tablesaw-mode="swipe">';
            echo '<thead>';
            echo '<tr><th data-tablesaw-priority="persist">Release/Description</th><th>Date/Notes</th><th>Zendesk ID</th><th>JIRA ID</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            // Sort releases by date
            krsort($fixes);

            // Build the table differently depending on $version_check value

            if ($version_check == false) {
              foreach ($fixes as $date => $ids) {
                foreach ($ids as $id => $patches) {
                  echo '<tr class="release-row rel-' . $id . '"><td>' . $id . '</td><td colspan="3">' . $date . '</tr>';

                  $patches_sort = $patches;

                  foreach ($patches_sort as $patch) {
                    echo '<tr class="rel-' .
                      $id .
                      '"><td class="patch-parent" data-tablesaw-priority="persist">' .
                      $patch['parent'] .
                      '</td><td class="patch-note">' .
                      $patch['note'] .
                      '</td><td class="patch-zendesk-id">' .
                      $patch['zendesk-id'] .
                      '</td><td class="patch-jira-id">' .
                      $patch['jira-id'] .
                      '</td></tr>';
                  }
                }
              }
            } else {
              foreach ($fixes as $date => $ids) {
                echo '<tr class="release-row"><td></td><td colspan="3">' . $date . '</tr>';

                // $patches_sort = $patches;

                foreach ($ids as $patch) {
                  echo '<tr><td class="patch-parent" data-tablesaw-priority="persist">' .
                    $patch['parent'] .
                    '</td><td class="patch-note">' .
                    $patch['note'] .
                    '</td><td class="patch-zendesk-id">' .
                    $patch['zendesk-id'] .
                    '</td><td class="patch-jira-id">' .
                    $patch['jira-id'] .
                    '</td></tr>';
                }
              }
            }

            echo '</tbody></table>';
            echo '</div>';
          } // End Build table
        } else {
          echo '<p class="ver-select-message">Sorry, there are no ticket details associated with that version number.</p>';
        }

/**
         * END JIRA API code
         */
?>
      </div>
    </div>
  </section>
<?php get_footer();
