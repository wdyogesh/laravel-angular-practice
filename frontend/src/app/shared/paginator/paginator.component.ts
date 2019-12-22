import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-paginator',
  templateUrl: './paginator.component.html',
  styleUrls: ['./paginator.component.scss']
})
export class PaginatorComponent implements OnInit {

  @Input() pagination:any;
  @Output() outputPagination = new EventEmitter<any>();

  constructor() {
  }

  ngOnInit() {
  }

  paginate (page_no, sign:string)
  {
    switch (sign) {
      case '+':
        this.pagination.page_no = page_no+1;
        break;
      case '-':
        this.pagination.page_no = page_no-1;
        break;
      default:
        break;
    }
    this.outputPagination.emit(this.pagination);
  }
}
