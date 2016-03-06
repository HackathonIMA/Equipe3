class Share < ActiveRecord::Base
  belongs_to :user
  belongs_to :school

  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }

  def Share.from_date(date)
    # Model.where('extract(year  from date_column) = ? AND extract(month from date_column) = ? AND extract(day   from date_column) = ?',
    #   desired_day_of_month)
    p date.to_time
    Share.where("created_at >= ?", date.to_time.beginning_of_day)
  end
end
