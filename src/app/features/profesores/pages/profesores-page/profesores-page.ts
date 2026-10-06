import { Component } from '@angular/core';
import { Header } from '../../../../layout/header/header';
import { Sidebar } from '../../../../layout/sidebar/sidebar';
import { UiIcon } from '../../../../shared/ui-icon/ui-icon';
import { ProfesoresFilters } from '../../components/profesores-filters/profesores-filters';
import { ProfesoresSummary } from '../../components/profesores-summary/profesores-summary';
import { ProfesoresTable } from '../../components/profesores-table/profesores-table';

@Component({
  selector: 'app-profesores-page',
  imports: [Header, Sidebar, UiIcon, ProfesoresFilters, ProfesoresSummary, ProfesoresTable],
  templateUrl: './profesores-page.html',
  styleUrl: './profesores-page.scss',
})
export class ProfesoresPage {}
